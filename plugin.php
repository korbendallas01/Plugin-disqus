<?php

class KokenDisqus extends KokenPlugin {

    function __construct()
	{
		$this->require_setup = true;
		$this->register_hook('before_closing_body', 'render_js');
		$this->register_hook('discussion', 'render_div');
		$this->register_hook('discussion_count', 'render_count_div');
	}

	function render_div($item)
	{
		echo '<script>var disqus_identifier = "koken_disqus_' . $item['__koken__'] . '_' . $item['id'] . '";</script><div id="disqus_thread"></div>';
	}

	function render_count_div($item)
	{
		echo '<a href="' . $item['url'] . '#disqus_thread" data-disqus-identifier="koken_disqus_' . $item['__koken__'] . '_' . $item['id'] . '"></a>';
	}

	function render_js()
	{
		echo <<<OUT
<script type="text/javascript">
	var disqus_shortname = '{$this->data->shortname}';
	(function() {
		var disqus = function() {
			if ($('#disqus_thread').length) {
				$.getScript(location.protocol + '//' + disqus_shortname + '.disqus.com/embed.js');
			}

			if ($('[data-disqus-identifier]').length) {
				window.DISQUSWIDGETS = undefined;
				$.getScript(location.protocol + '//' + disqus_shortname + '.disqus.com/count.js');
			}
		}

		$(function() {
			if ($.pjax) {
				$(document).on('pjax:success pjax:end', function() {
					window.setTimeout(function() {
						if (window.DISQUS && $('#disqus_thread').length) {
							window.DISQUS.reset({
								reload: true,
								config: function() {
									this.page.url = window.location.href;
									this.page.identifier = disqus_identifier;
								}
							});
						} else {
							disqus();
						}
					}, 250)
				});
			}
			disqus();
		});
	}());
</script>
OUT;

	}
}
