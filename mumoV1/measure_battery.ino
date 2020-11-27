int measure_battery() {
  //Switch outputs low to do a clean measurement
  pinMode(CHARGE_STATUS_PIN, OUTPUT);
  digitalWrite(CHARGE_STATUS_PIN, LOW);
  delay(50);
  //Measure 3 times and map to percentage
  int raw = analogRead(BATTERY_POWER_PIN) + analogRead(BATTERY_POWER_PIN) + analogRead(BATTERY_POWER_PIN);
  int percentage = map(raw, 345, 390, 0, 100);
  //Back to input to save power
  pinMode(CHARGE_STATUS_PIN, INPUT);
  //constrain to realistic range for rechargeable batteries
  percentage = constrain(percentage, 0, 105);
  return percentage;
}
