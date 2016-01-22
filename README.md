#Slack Notifier
A package for easy implementing slack notifications in your own project  

##Installation
Install the composer package (https://packagist.org/packages/sanderheijselaar/ezdebug)
or download and include the ezDebug.php file in your project

##Quick Usage

	use SanderHeijselaar\SlackNotifier\SlackNotifier;

##Emoji
If you also want to use the available emoji from class constants

	use SanderHeijselaar\SlackNotifier\Emoji;
		
##Config
When adding a web hook, you'll get an url like:
https://hooks.slack.com/services/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

Split this url in two and add it to a config array like below. 

The 'exceptions' parameter is to tell Guzzle to throw exceptions or not. If set to true, you'll have to catch them yourself. When set to false, the errors are returned by the sendNotification method. 

	$config = array(
		"base_url"   => "https://hooks.slack.com",
		"uri"        => "/services/T44G09RET/B03YRHFIP/LpySUOJuC8GxA2kg419qfSpl",
		"exceptions" => false
	);


##First example
A simple example to send a basic notification

	// Set the message of the notification
	$message = "My notification";
	// Set the name of the sender. When left empty, the name you entered on the setup of the integration will be used.
	$name    = "Bot";
	// Set the recieving channel. When left empty, the name you entered on the setup of the integration will be used.
	$channel = '';
	// Optional emoji. You can use the SanderHeijselaar\SlackNotifier\Emoji class for this
	$emoji = Emoji::WHITE_CHECK_MARK;
	
	// Init the class 
	$i = new SlackNotifier($config);
	// Send the notification
	$result = $i->sendNotification($message, $name, $channel, $emoji, true);

	// Dump the result. This is the returned result of Class \GuzzleHttp\Client method post() 
	echo '<pre>' . print_r(json_decode($result, true), true) . '</pre>';

Result: 

![Alt text](raw/img/example-01.png?raw=true "First example")

##Attachments
###Basics
Now let's add a basic attachment

	// Set the message of the notification
	$message = "My notification";
	// Set the name of the sender. When left empty, the name you entered on the setup of the integration will be used.
	$name    = "Bot";
	// Set the recieving channel. When left empty, the name you entered on the setup of the integration will be used.
	$channel = '';
	// Optional emoji. You can use the SanderHeijselaar\SlackNotifier\Emoji class for this
	$emoji = Emoji::WHITE_CHECK_MARK;
	
	// Init the class 
	$i = new SlackNotifier($config);
	
	// Add a new attachment. You can add multiple attachments by repeating these steps
	$i->newAttachment()
	// Add a fallback text
	->addAttachmentFallback('Put your fallback text here without mark up')
	// Set the color of the attachment. There are three basic colors: good, warning & danger. You can also add hex colors like #ff00ff
	->addAttachmentColor(SlackNotifier::COLOR_GOOD)
	// Add the attachment text here. The secont parameter is to enable the mark up on this text so that the text between the * is displayed bold. 
	->addAttachmentText('Text *here*', true);

	// Send the notification
	$result = $i->sendNotification($message, $name, $channel, $emoji, true);

	// Dump the result. This is the returned result of Class \GuzzleHttp\Client method post() 
	echo '<pre>' . print_r(json_decode($result, true), true) . '</pre>';

Result: 

![Alt text](raw/img/example-02.png?raw=true "Second example")

###Pretext
By adding a pretext to the attachment, a line of text above the attachment is displayed. Italic styling is added by putting text between _

	->addAttachmentPreText("_Pre Text_", true)

Result: 

![Alt text](raw/img/example-03.png?raw=true "Third example")

###Author
By adding an author to the attachment, a line of text will be displayed in the attachment. Here my name is displayed. It's also linked to the provided url. The fird parameter is a link to a 16x16 size icon which will be displayed on the left side before the name.

	->addAttachmentAuthor('Sander', 'http://www.google.com', 'https://jenkins-assembler.googlecode.com/files/griffon-icon-16x16.png')


Result: 

![Alt text](raw/img/example-04.png?raw=true "Fourth example")

###Title
By adding a title to the attachment, a line of text will be displayed in the attachment between the author and the text. It's also linked to the provided url. 

	->addAttachmentTitle('Title', 'http://www.title.com')


Result: 

![Alt text](raw/img/example-05.png?raw=true "Fifth example")

###Image
By adding an image to the attachment, a thumb image (second argument) will be displayed and linked to the image (first argument). 

	->addAttachmentImage('http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png', 'http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png')


Result: 

![Alt text](raw/img/example-06.png?raw=true "Sixth example")

###Fields
It's possible to add field to dislay additional short data.  

	->addAttachmentImage('http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png', 'http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png')


Result: 

![Alt text](raw/img/example-07.png?raw=true "Seventh example")

