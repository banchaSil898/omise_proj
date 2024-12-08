<form name=sendform method=post action="https://rt05.kasikornbank.com/pgpayment/payment.aspx">
  <INPUT type="hidden" id=MERCHANT2 name=MERCHANT2 value="401001542055001">
  <INPUT type="hidden" id=TERM2 name=TERM2 value="70348628">
  Amount :<INPUT type="text" id=AMOUNT2 name=AMOUNT2 value="000000000100">
  Back URP :<INPUT type="text" id=URL2 name=URL2 value="">
  <INPUT type="hidden" id=RESPURL name=RESPURL value="">
  <INPUT type="hidden" id=IPCUST2 name=IPCUST2 value=""> 
  Detail : <INPUT type="text" id=DETAIL2 name=DETAIL2 value="Test Payment"> 
  Invoice No :<INPUT type="text" id=INVMERCHANT name=INVMERCHANT value="900000000001">
</form>
<button onclick="document.sendform.submit();">Submit</button><!--<script>document.sendform.submit();</script>-->