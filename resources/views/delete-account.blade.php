<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Deletion & Privacy Policy</title>
        <style>
            body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:#f3f4f6; margin:0; }
            .wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
            .card { background:#fff; padding:28px; border-radius:8px; box-shadow:0 8px 30px rgba(0,0,0,0.06); max-width:900px; }
            h1 { margin:0 0 8px 0; font-size:22px; color:#111827 }
            h2 { margin-top:18px; font-size:16px; color:#111827 }
            p, li { color:#374151; line-height:1.5 }
            ul { margin:8px 0 0 20px }
            .muted { color:#6b7280; font-size:13px }
            a { color:#2563eb; text-decoration:none }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                <h1>Data Deletion Instructions & Privacy Policy</h1>
                <p class="muted">Last updated: 2025-12-07</p>

                <h2>1. Data Deletion Instructions (Facebook Requirement)</h2>
                <p>If you want to delete your data that has been collected through our Facebook App, you can do so at any time by following the steps below:</p>

                <h3>How to Request Data Deletion</h3>
                <p>To request deletion of your data, please follow one of these methods:</p>
                <ul>
                    <li><strong>Send us a deletion request</strong></li>
                    <li><strong>Email us</strong> at <em>[your email]</em> with the subject line: “Facebook Data Deletion Request”</li>
                </ul>

                <p>Please include the following information in your request:</p>
                <ul>
                    <li>Full Name</li>
                    <li>Facebook User ID or the email connected to your Facebook account</li>
                </ul>

                <p><strong>Automatic Deletion:</strong> If you remove our app from your Facebook account settings, all information connected to your ID will be automatically deleted from our system within 24–48 hours.</p>

                <h2>2. What Data We Collect</h2>
                <p>Our system may collect the following information when you use our Facebook App:</p>
                <ul>
                    <li>Public profile information (name, profile picture, email)</li>
                    <li>Any data required only to provide the app’s features</li>
                </ul>
                <p>We do not sell, share, or use your data for any purpose outside the app's functionality.</p>

                <h2>3. Why We Collect Data</h2>
                <p>Your information is used only to:</p>
                <ul>
                    <li>Provide access to the app</li>
                    <li>Personalize user experience</li>
                    <li>Improve service functionality</li>
                </ul>
                <p>We do not use your data for advertising or unrelated purposes.</p>

                <h2>4. Data Retention</h2>
                <p>Your data is stored only as long as required to operate the service. Once you request deletion or remove the app, all associated data is permanently erased.</p>

                <h2>5. Contact Information</h2>
                <p>If you have any questions about this Privacy or Data Deletion Policy, contact us at: <em>[your email]</em></p>

                <p style="margin-top:18px;"><a href="/">← Back to home</a></p>
            </div>
        </div>
    </body>
</html>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title', 'WATIQ by Cloudtech')</title>
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
				<h1>@yield('title', 'WATIQ by Cloudtech')</h1>
				<p style="margin-top:8px; font-weight:700;">Welcome</p>
				<p style="margin-top:6px; color:#6b7280;">This website is a testing environment for WhatsApp Business Platform integrations and webhook handling.</p>
				@if(isset($webhookCount))
					<p style="margin-top:10px; font-weight:600;">Webhooks received: <span style="background:#eef; padding:4px 8px; border-radius:6px;">{{ $webhookCount }}</span></p>
				@endif

				<p style="margin-top:12px;">
					<a href="/privacy-policy" style="margin-right:12px; color:#2563eb; text-decoration:none;">Privacy Policy</a>
					<a href="/terms-of-service" style="color:#2563eb; text-decoration:none;">Terms of Service</a>
				</p>
			</div>
		</div>
	</body>
</html>

