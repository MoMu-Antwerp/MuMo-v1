# The Things Network Payload format

### Decoder
upload this code in to the decoder tab of the application:
```
function Decoder(bytes, port) {
  // Decode an uplink message from a buffer
  // (array) of bytes to an object of fields.
  var decoded = {};

  decoded.version = bytes[0];
  if(decoded.version == 1){
    decoded.battery = bytes[1];
    decoded.humidity = bytes[2];
    decoded.humidity += (bytes[3]/100);
    decoded.temperature = bytes[4];
    decoded.temperature += (bytes[5]/100);
    decoded.pressure = (bytes[6])<<8;
    decoded.pressure += (bytes[7]);
    decoded.pressure =  (decoded.pressure-30000+101325)/100;
    decoded.illumination = (bytes[8]<<8);
    decoded.illumination += bytes[9];
  }
  return decoded;
}
```

# Human readable format
## Uplink
#### BIT: ACTION
- 0: version number (For now V1)
- 1: Battery level (indication)[0-100%]
- 2: Air moisture (above decimal place)[0 - 255%]
- 3: Air moisture (after decimal place)[0.00-0.99% * 100]
- 4: Air Temperature ((above decimal place)[0 - 255°C]
- 5: Air Temperature (after decimal place)[0.00-0.99°C * 100]
- 6: Air pressure (Big byte << 8)[offset from baseline 101325 pA pressure]
- 7: Air pressure (Small byte) 
- 8: Lux (Big byte)[]
- 9: Lux (Small byte)

## Downlink
#### BIT: ACTION
- 0: Maximal temperature (devided by 5)[0-50°C => 0-250]
- 1: Minimal temperature (devided by 5)[0-50°C => 0-250]
- 2: Maximal humidity (devided by 2)[0-100% = 0-200]
- 3: Minimal humidity (devided by 2)[0-100% = 0-200]
- 4: Maximal light (Big byte)[0-65000 Lux]
- 5: Maximal light (Small byte)
- 6: Maximal pressure offset (Big byte)[offset from baseline 101325 Pa pressure]
- 7: Maximal pressure offset (Small byte)
- 8: Sleepcycle (minutes between data transmissions)[0-255 minutes]
