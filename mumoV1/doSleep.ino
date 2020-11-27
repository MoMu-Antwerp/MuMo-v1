void doSleep() {
  SysTick->CTRL &= ~SysTick_CTRL_TICKINT_Msk;
  rtc.standbyMode();            // bring CPU into deep sleep mode (until woken up by the RTC)
  SysTick->CTRL |= SysTick_CTRL_TICKINT_Msk;
}
