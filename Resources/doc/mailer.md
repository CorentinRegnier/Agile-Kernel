Agile Kernel Bundle
=================

Mailer Documentation
--------------------

### Configuration

Verify your `swiftmailer` configuration:

```yaml
# /app/config/config.yml

swiftmailer:
    transport: "%mailer_transport%"
    host:      "%mailer_host%"
    port:      "%mailer_port%"
    username:  "%mailer_user%"
    password:  "%mailer_password%"
```

You can change `sender_address` and `sender_name` parameter.

```yaml
# /app/config/agile.yml

agile_kernel:
    # ...
    mailer:
        sender_address: no-reply@acmedemo.com
        sender_name: ACME
```

### Usage

#### Mail template

All parts of the mail are in one template.
In your mail template, the following blocks must be present:
- `subject` Define the subject of the mail (plain text)
- `body_text` The text/plain part of the mail
- `body_html` The text/html part of the mail

This bundle provides a mail layout (`AgileKernelBundle:mail:base.html.twig`) implementing these blocks for you.
You just have to fill the custom and the signature will be appended.

You can use this template and write your mail this way:

```django
{% extends 'AgileKernelBundle:mail:base.html.twig' %}

{% block subject %}
	Welcome!
{% endblock %}

{% block content_text %}
	Hello,

	How are you?
	 \o/
	  |
	 /\

	See you!
{% endblock %}

{% block content_html %}
	<p>Hello,</p>

	<p>How are you?</p>
	<img src="http://path/to/img">
	<hr>
	<p>See you!</p>
{% endblock %}
```

#### Send emails

On the backend side you can use the `agile_kernel.mailer` service:

```php
$mailer = $this->container->get('agile_kernel.mailer');
$mailer->send('AcmeDemoBundle:mail:my_custom_mail.html.twig',
	'recipient@website.com',
	['message' => $message]
);
```

An optional 4th argument is used to override the sender email.

If you intend to send a mail to the authenticated user, you should use:

```php
$mailer->sendToUser(
	'AcmeDemoBundle:mail:my_custom_mail.html.twig',
	['message' => $message],
	$user
);
```

#### Attachments

You can pass some files path to the mailer service to attach these ones to the mail:

```php
$mailer->send('AcmeDemoBundle:mail:my_custom_mail.html.twig',
	'recipient@website.com',
	['message' => $message],
	null, // sender email, by default its the one set in your configuration
	['/var/www/uploads/a.jpg', '/var/www/static/demo.pdf']
);
```

#### Locale

You can choose the locale for the email if you use the `send` method. If you send the email directly to an user with the `sendToUser` method, the locale will be the user's one.

#### Tags

You can pass an array tags (used for tracking) as last argument. Right now, it's only working if the `mailer_host` parameter is set to `smtp.sendgrid.net` or `smtp.mandrillapp.com`.

#### Command line

In order to test your mail configuration you can send test mails with the command `agile:mailer:send`:

```bash
$ bin/console agile:mailer:send xxx@mentalworks.fr 'AgileKernelBundle:mail:test.html.twig' -a /path/to/attached-file
.pdf -t tag1 -t tag2
```

[Return to index](index.md)
