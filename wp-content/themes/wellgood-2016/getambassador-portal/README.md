### Get Abmassador Setup
---

1. Goto Portal
2. Under Header Scripts add for the fonts

```
<script type="text/javascript">
  // Typekit
  (function() {
    var tk = document.createElement('script');
    var d = false;
    tk.src = '//use.typekit.net/wbd6gie.js';
    tk.type = 'text/javascript';
    tk.async = true;
    tk.onload = tk.onreadystatechange = function() {
      var rs = this.readyState;
      if (d || rs && rs != 'complete' && rs != 'loaded') return;
      d = true;
      try { Typekit.load({async: true}); } catch (e) {}
    };
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(tk, s);
  })();
</script>
```

3. Under Header Scripts for Development add

```
<script>
var link = document.createElement('link');
    link.id = 'id2';
    link.rel = 'stylesheet';
    link.href = 'http://localhost:3000/style.css';
    document.head.appendChild(link);
</script>
```

The `http://localhost:3000/style.css` will need to be switched out for the production styles.

4. npm start runs the gulp


### Build
---

`npm run build` runs the build, post build copy over the udpated CSS to S3