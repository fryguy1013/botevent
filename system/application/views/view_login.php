<html>
  <head><title>Login</title></head>
  <style type="text/css">
      * {
        font-family: verdana,sans-serif;
      }
      body {
        width: 50em;
        margin: 1em;
      }
      div {
        padding: .5em;
      }
      table {
        margin: none;
        padding: none;
      }
      .alert {
        border: 1px solid #e7dc2b;
        background: #fff888;
      }
      .success {
        border: 1px solid #669966;
        background: #88ff88;
      }
      .error {
        border: 1px solid #ff0000;
        background: #ffaaaa;
      }
      #verify-form {
        border: 1px solid #777777;
        background: #dddddd;
        margin-top: 1em;
        padding-bottom: 0em;
      }
  </style>
  <body>
    <h1>Login</h1>

    <?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; } ?>
    <?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class=\"success\">$success</div>"; } ?>

    <div id="verify-form">
      <form method="post" action="<?php echo /*site_url('login');*/"/login"; ?>">
        Identity&nbsp;URL:
        <input type="hidden" name="action" value="verify" />
        <input type="text" id="openid_identifier" name="openid_identifier" value="" size="40"/>

        <input type="submit" value="Verify" />
      </form>
    </div>
	
	<!-- BEGIN ID SELECTOR -->
	<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/selector/35368b9e05ddccf9f618b2b8e6ffe71ab10ca2fc" charset="utf-8"></script>
	<!-- END ID SELECTOR -->	
  </body>
</html>