<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Libyan Center</title>
		<style>
			body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:#f3f4f6; margin:0; }
			.wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; }
			.card { background:#fff; padding:40px; border-radius:8px; box-shadow:0 8px 30px rgba(0,0,0,0.06); text-align:center; }
			h1 { margin:0 0 8px 0; font-size:28px; color:#111827 }
			p { margin:0; color:#6b7280 }
		</style>
	</head>
	<body>
		<div class="wrap">
			<div class="card">
				<h1>Libyan Center</h1>
				<p>Welcome — this website is a testing environment for WhatsApp Business Platform integrations and webhook handling.</p>
				@if(isset($webhookCount))
					<p style="margin-top:10px; font-weight:600;">Webhooks received: <span style="background:#eef; padding:4px 8px; border-radius:6px;">{{ $webhookCount }}</span></p>
				@endif
				<p style="margin-top:12px;">
					Useful docs: <a href="https://developers.facebook.com/docs/whatsapp" target="_blank">WhatsApp Business Platform Docs</a>
				</p>
			</div>
		</div>
	</body>
</html>

