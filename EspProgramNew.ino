#include <Wire.h>
#include <Adafruit_INA219.h>
#include <WiFi.h>
#include <PubSubClient.h>

// WIFI
const char* ssid = "Xiaomi 15T";
const char* password = "12345678";

// MQTT
const char* mqtt_server = "10.124.245.24";
const int mqtt_port = 1883;

const char* mqtt_topic_data = "surya/panel1/data";
const char* mqtt_topic_relay = "kontrol/panel1/relay";

WiFiClient espClient;
PubSubClient client(espClient);

const int pinSSR = 5;
Adafruit_INA219 ina219;

unsigned long lastReadMs = 0;
const unsigned long intervalMs = 2000;

const float voltaseKosong = 9.00;
const float voltasePenuh  = 13.00;

float lastVoltage = 0;
float lastCurrentA = 0;
int lastSoc = 0;
String lastStatus = "UNKNOWN";

String relayState = "OFF";

void applyRelayState() {
  digitalWrite(pinSSR, (relayState == "ON") ? HIGH : LOW);
}

void mqttCallback(char* topic, byte* payload, unsigned int length) {
  String msg;
  msg.reserve(length);
  for (unsigned int i = 0; i < length; i++) msg += (char)payload[i];
  msg.trim();
  msg.toUpperCase();

  if (String(topic) == mqtt_topic_relay) {
    if (msg == "ON" || msg == "OFF") {
      relayState = msg;
      applyRelayState();
      Serial.print("Relay cmd diterima: ");
      Serial.println(relayState);
    }
  }
}

void reconnectMQTT() {
  while (!client.connected()) {
    Serial.print("Menghubungkan ke MQTT broker...");
    String clientId = "ESP32_Solar_Client_" + String((uint32_t)ESP.getEfuseMac(), HEX);

    if (client.connect(clientId.c_str())) {
      Serial.println(" Terhubung!");
      client.subscribe(mqtt_topic_relay);
      Serial.print("Subscribe: ");
      Serial.println(mqtt_topic_relay);
    } else {
      Serial.print(" Gagal, rc=");
      Serial.print(client.state());
      Serial.println(" - coba lagi 5 detik");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);

  pinMode(pinSSR, OUTPUT);
  relayState = "OFF";
  applyRelayState();

  if (!ina219.begin()) {
    Serial.println("Gagal menemukan chip INA219! Periksa kabel SDA/SCL.");
    while (1) delay(10);
  }

  Serial.print("Menghubungkan ke WiFi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi terhubung!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());

  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(mqttCallback);
}

void loop() {
  if (!client.connected()) reconnectMQTT();
  client.loop();

  unsigned long now = millis();
  if (now - lastReadMs >= intervalMs) {
    lastReadMs = now;

    lastVoltage = ina219.getBusVoltage_V();
    float current_mA = ina219.getCurrent_mA();
    lastCurrentA = current_mA / 1000.0;
    if (lastCurrentA < 0) lastCurrentA = 0;

    if (lastVoltage >= voltasePenuh) lastSoc = 100;
    else if (lastVoltage <= voltaseKosong) lastSoc = 0;
    else lastSoc = (int)((lastVoltage - voltaseKosong) / (voltasePenuh - voltaseKosong) * 100);

    lastStatus = (lastVoltage < voltasePenuh) ? "CHARGING" : "FULL";

    char payload[160];
    snprintf(payload, sizeof(payload),
             "{\"tegangan\":%.2f,\"arus\":%.3f,\"soc\":%d,\"status\":\"%s\",\"relay\":\"%s\"}",
             lastVoltage, lastCurrentA, lastSoc, lastStatus.c_str(), relayState.c_str());

    client.publish(mqtt_topic_data, payload);
    Serial.print("MQTT data: ");
    Serial.println(payload);
  }
}
