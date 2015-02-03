<script type="text/javascript">
	{{ Notification::container('backendFeedback')->showError('
		alertify.error(":message");
	') }}
	
	{{ Notification::container('backendFeedback')->showWarning('
		alertify.error(":message");
	') }}

	{{ Notification::container('backendFeedback')->showInfo('
		alertify.log(":message");
	') }}

	{{ Notification::container('backendFeedback')->showSuccess('
		alertify.success(":message");
	') }}
</script>