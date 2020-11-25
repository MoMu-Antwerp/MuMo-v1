void doSleep() {
  rtc.setAlarmEpoch(nextAlarmClock);
  nextAlarmClock += sleepcycle;
  rtc.enableAlarm(rtc.MATCH_HHMMSS);
  rtc.standbyMode();            // bring CPU into deep sleep mode (until woken up by the RTC)
}
