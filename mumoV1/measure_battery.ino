int measure_battery() {
  pinMode(CHARGE_STATUS_PIN, OUTPUT);
  digitalWrite(CHARGE_STATUS_PIN, LOW);
  delay(50);
  int raw = analogRead(BATTERY_POWER_PIN) + analogRead(BATTERY_POWER_PIN) + analogRead(BATTERY_POWER_PIN);
  int percentage = map(raw / 3, 115, 130, 0, 100);
  pinMode(CHARGE_STATUS_PIN, INPUT);
  percentage = constrain(percentage, 0, 105);
  return percentage;
}
