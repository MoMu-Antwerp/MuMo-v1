void update_thresholds() {
  Serial.println("!!!!!!!!! THRESHOLD CHANGE !!!!!!!!!!");

  //Set the maximal_temperature threshold
  maximal_temperature.write((float)downlinkData[0] / 5.00);

  //Set the minimal_temperature threshold
  minimal_temperature.write((float)downlinkData[1] / 5.00);

  //Set the maximal_humidity threshold
  maximal_humidity.write((float)downlinkData[2] / 2.00);

  //Set the minimal_humidity threshold
  minimal_humidity.write((float)downlinkData[3] / 2.00);

  //Set the maximal_illumination threshold
  long combined_light_data = downlinkData[4] << 8;
  combined_light_data += downlinkData[5];
  maximal_illumination.write(combined_light_data);

  //Set the range_pressure threshold
  long combined_pressure_data = downlinkData[6] << 8;
  combined_pressure_data += downlinkData[7];
  range_pressure.write(combined_pressure_data);

  //Set sleepcycle
  sleepCycli.write(downlinkData[8]);
}
