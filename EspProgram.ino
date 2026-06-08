#include <Wire.h>
#include <Adafruit_INA219.h>
#include <WiFi.h>
#include <PubSubClient.h>

// ==================== KONFIGURASI WIFI ====================
const char* ssid = "Xiaomi 15T";           // GANTI dengan WiFi Anda
const char* password = "12345678";   // GANTI dengan password WiFi

// ==================== KONFIGURASI MQTT ====================
const char* mqtt_server = "10.12.206.24";       // GANTI dengan IP Laptop Anda
const int mqtt_port = 1883;
const char* mqtt_topic = "surya/panel1/data";

WiFiClient espClient;
PubSubClient client(espClient);

// ==================== KODE ASLI ANDA (TIDAK BERUBAH) ====================
const int pinSSR = 5;
Adafruit_INA219 ina219;

unsigned long waktuTerakhir = 0;
const unsigned long jedaBaca = 2000; // Update data setiap 2 detik

// Setting skala baterai
const float voltaseKosong = 09.00;
const float voltasePenuh  = 12.50;

// Timer untuk MQTT (kirim setiap 5 detik, sinkron dengan jedaBaca)
unsigned long waktuTerakhirMQTT = 0;
const unsigned long jedaMQTT = 2000;

// ==================== SETUP ====================
void setup() {
  Serial.begin(115200);
  pinMode(pinSSR, OUTPUT);
  digitalWrite(pinSSR, LOW);

  Serial.println("--- Menginisialisasi Sensor INA219 (Skala 11V - 12.5V) ---");
  
  if (!ina219.begin()) {
    Serial.println("Gagal menemukan chip INA219! Periksa kabel SDA/SCL.");
    while (1) { delay(10); }
  }
  
  Serial.println("Sensor Terdeteksi! Memulai pembacaan real-time...");
  Serial.println("---------------------------------------------------------");

  // ==================== TAMBAHAN: SETUP WIFI ====================
  Serial.print("Menghubungkan ke WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi terhubung!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());

  // ==================== TAMBAHAN: SETUP MQTT ====================
  client.setServer(mqtt_server, mqtt_port);
}

// ==================== TAMBAHAN: FUNGSI KONEK MQTT ====================
void reconnectMQTT() {
  while (!client.connected()) {
    Serial.print("Menghubungkan ke MQTT broker...");
    if (client.connect("ESP32_Solar_Client")) {
      Serial.println(" Terhubung!");
    } else {
      Serial.print(" Gagal, rc=");
      Serial.print(client.state());
      Serial.println(" - coba lagi 5 detik");
      delay(5000);
    }
  }
}

// ==================== LOOP ====================
void loop() {
  // Jaga koneksi MQTT
  if (!client.connected()) {
    reconnectMQTT();
  }
  client.loop();

  unsigned long waktuSekarang = millis();

  if (waktuSekarang - waktuTerakhir >= jedaBaca) {
    waktuTerakhir = waktuSekarang;

    // --- A. BACA DATA NYATA DARI SENSOR ---
    float busVoltage = ina219.getBusVoltage_V();
    float current_mA = ina219.getCurrent_mA();
    float current_A  = current_mA / 1000.0;

    // --- B. RUMUS HITUNG PERSENTASE BATERAI ---
    int socPercent = 0;
    
    if (busVoltage >= voltasePenuh) {
      socPercent = 100;
    } else if (busVoltage <= voltaseKosong) {
      socPercent = 0;
    } else {
      socPercent = (int)((busVoltage - voltaseKosong) / (voltasePenuh - voltaseKosong) * 100);
    }

    // --- C. LOGIKA OTOMATIS RELAY / SSR ---
    String statusRelay = "";
    if (busVoltage < voltasePenuh) {
      statusRelay = "ON (PWM 50% - Sedang Mengisi Aki)";
      
      // Efek fisik kedap-kedip SSR
      digitalWrite(pinSSR, HIGH); 
      delay(500); 
      digitalWrite(pinSSR, LOW);
    } else {
      statusRelay = "OFF (Aki Sudah Penuh - Overcharge Protection)";
      digitalWrite(pinSSR, LOW);
    }

    // --- D. TAMPILKAN OUTPUT KE SERIAL MONITOR ---
    Serial.println("\n===== DATA REAL-TIME PLTS POLIBATAM =====");
    Serial.print("Tegangan Aki Asli : "); Serial.print(busVoltage, 2); Serial.println(" V");
    Serial.print("Arus Pengisian    : "); Serial.print(current_A, 3);  Serial.println(" A");
    Serial.print("Kapasitas Baterai : "); Serial.print(socPercent);    Serial.println(" %");
    Serial.print("Status Pengisian  : "); Serial.println(statusRelay);
    Serial.println("==========================================");
  }

  // ==================== TAMBAHAN: KIRIM DATA VIA MQTT ====================
  if (millis() - waktuTerakhirMQTT >= jedaMQTT) {
    waktuTerakhirMQTT = millis();
    
    // Baca ulang data terbaru (atau bisa pakai variabel dari atas)
    float tegangan = ina219.getBusVoltage_V();
    float arus_mA = ina219.getCurrent_mA();
    float arus_A = arus_mA / 1000.0;
    if (arus_A < 0) arus_A = 0;
    
    int soc = 0;
    if (tegangan >= voltasePenuh) {
      soc = 100;
    } else if (tegangan <= voltaseKosong) {
      soc = 0;
    } else {
      soc = (int)((tegangan - voltaseKosong) / (voltasePenuh - voltaseKosong) * 100);
    }
    
    String status = (tegangan < voltasePenuh) ? "CHARGING" : "FULL";
    
    // Buat pesan JSON
    char pesanJSON[128];
    snprintf(pesanJSON, sizeof(pesanJSON),
             "{\"tegangan\":%.2f,\"arus\":%.3f,\"soc\":%d,\"status\":\"%s\"}",
             tegangan, arus_A, soc, status.c_str());
    
    if (client.publish(mqtt_topic, pesanJSON)) {
      Serial.print("📤 MQTT Terkirim: ");
      Serial.println(pesanJSON);
    } else {
      Serial.println("❌ MQTT Gagal terkirim");
    }
  }
}
