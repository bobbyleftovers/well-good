<?php if ( empty( $amp ) ): ?>
	<script type="text/javascript" class="teads" async="true" src="//a.teads.tv/page/73565/tag"></script>

<?php else: ?>
	<script>
        (function(w, d) {
            var pageId = 73568;
            w._teads_amp = {};
            var s = d.createElement('script');
            s.async = true;
            s.onload = function() {
                if (d.getElementById(window.name)) {
                    d.getElementById(window.name).style.display = "none"
                }
            };
            s.src = 'https://a.teads.tv/page/' + pageId + '/tag';
            d.body.appendChild(s);
        })(parent, parent.document);
	</script>
<?php endif; ?>