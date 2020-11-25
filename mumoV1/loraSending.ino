boolean loraSending() {
  lora.setPower(20);
  Serial.println("LoRa Awake!");
  delay(200);

  Serial.println("<<<<<< Sending package to TTN! >>>>>>");
  if (lora.transferPacket(data, 10)) {
    short length;
    short rssi;
    //Prepare memory and buffer for possible downlink
    memset(buffer, 0, 256);
    length = lora.receivePacket(buffer, 256, &rssi);

    if (length == 9) {
      Serial.println("<<<<<< Downlink message received! >>>>>>");

      for (int i = 0; i < length; i++) {
        downlinkData[i] = buffer[i];
      }
      return true;
    }else if(length){
      Serial.println("<<<<<< Downlink message received, but of wrong length! >>>>>>");      
    } else {
      Serial.println("<<<<<< No Downlink message received! >>>>>>");
    }
  }
  return false;
}
