<div id="mainmenu">
    <div class="menuhead"><a href="../meter_readings.php">Main Menu</a></div>
    <br />
  Transactions
        <div class="submenu">
           <li> <div <?php if (strpos($_SERVER['PHP_SELF'], 'meter_form.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="meter_form.php">Meters Entry</a></div></li>
            <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'payment_form_admin.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="payment_form_admin.php">Payment</a></div></li>
             <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'cashentry.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="cashentry.php">Cash Entry</a></div></li>
           <li><div <?php if (strpos($_SERVER['PHP_SELF'], '#')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="invoice_service_form.php">Invoice Service</a></div></li>
         <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'invoice_form_admin.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="invoice_form_admin.php">Invoice</a></div></li>
         <li>
           <div <?php if (strpos($_SERVER['PHP_SELF'], 'post_entries.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="post_entries.php">Post Entries</a></div></li>
        </div>
  Reports
        <div class="submenu">
          <li> <div <?php if (strpos($_SERVER['PHP_SELF'], 'customer_statements.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="customer_statements.php">Customer</a></div></li>
            <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'product_statement.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="product_statement.php">Product</a></div></li>
           <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'meter_statement.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="meter_statement.php">Meters</a></div></li>
          <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'daily_summary.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="daily_summary.php">Daily Summary</a></div></li>
         <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'cash_sales.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="cash_sales.php">Cash Sales</a></div></li>
         </div>
  Change/Check
      <div class="submenu">
         <li> <div <?php if (strpos($_SERVER['PHP_SELF'], 'change_price_admin.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="change_price_admin.php">Pump Price</a></div></li>
          <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'edit_entries.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="edit_entries.php">Entries</a></div></li>
      </div>
  Accounts
        <div class="submenu">
            <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'addacc.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="addacc.php">Add Account</a></div></li>
             <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'subledger_accounts.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="subledger_accounts.php">SubLedger Accounts</a></div></li>
            <li><div <?php if (strpos($_SERVER['PHP_SELF'], 'nominal_accounts.php')){ echo "class='selected'";}else{echo "class='sublink'";}?>><a href="nominal_accounts.php">Nominal Accounts</a></div></li>
        </div>
    </div>