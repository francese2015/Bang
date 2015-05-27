function MainWindow() {

	var MainView = require('ui/MainView');
	var mainView = new MainView();
	
	var notify = require('bencoding.localnotify');

	var tutorialButton = Titanium.UI.createButton({
		title : 'Tutorial'
	});

	tutorialButton.addEventListener('click', function() {
		var Tutorial = require('ui/tutorial/Tutorial');
		var tutorial = new Tutorial();
		tutorial.open();
	});
	
	var settingsButton = Titanium.UI.createButton({
		backgroundColor : 'transparent',
		backgroundImage : 'images/settings.png',
		width : 25,
		height : 20
	});
	
	settingsButton.addEventListener('click', function(){
		var Settings = require('ui/settings/Settings');
		var settings = new Settings();
		global.mainTab.open(settings,{
			transition : Ti.UI.iPhone.AnimationStyle.FLIP_FROM_LEFT
		});
	});

	/*var volume = Ti.App.Properties.getDouble('volume', 0.4);

	var volumeButton4 = Titanium.UI.createButton({
		backgroundColor : 'transparent',
		backgroundImage : 'images/volume4.png',
		width : 25,
		height : 20
	});

	var volumeButton3 = Titanium.UI.createButton({
		backgroundColor : 'transparent',
		backgroundImage : 'images/volume3.png',
		width : 25,
		height : 20
	});

	var volumeButton2 = Titanium.UI.createButton({
		backgroundColor : 'transparent',
		backgroundImage : 'images/volume2.png',
		width : 25,
		height : 20
	});

	var volumeButton1 = Titanium.UI.createButton({
		backgroundColor : 'transparent',
		backgroundImage : 'images/volume1.png',
		width : 25,
		height : 20
	});

	var volumeChangeCallback = function() {
		switch(volume) {
		case 1.0:
			//lo passo a volume 0.0
			self.rightNavButton = volumeButton1;
			Ti.App.Properties.setDouble('volume', 0.0);
			volume = 0.0;
			player.volume = 0.0;
			break;
		case 0.7:
			//lo passo a volume 1.0
			self.rightNavButton = volumeButton4;
			Ti.App.Properties.setDouble('volume', 1.0);
			player.volume = 1.0;
			volume = 1.0;
			break;
		case 0.4:
			//lo passo a volume 0.7
			self.rightNavButton = volumeButton3;
			Ti.App.Properties.setDouble('volume', 0.7);
			player.volume = 0.7;
			volume = 0.7;
			break;
		case 0.0:
			//lo passo a volume 0.4
			self.rightNavButton = volumeButton2;
			Ti.App.Properties.setDouble('volume', 0.4);
			player.volume = 0.4;
			volume = 0.4;
			break;
		}
	};

	volumeButton1.addEventListener('click', volumeChangeCallback);
	volumeButton2.addEventListener('click', volumeChangeCallback);
	volumeButton3.addEventListener('click', volumeChangeCallback);
	volumeButton4.addEventListener('click', volumeChangeCallback);

	var player = Ti.Media.createSound({
		url : "music/theme.mp3",
		looping : true,
		volume : volume
	});

	player.play();*/

	var self = Titanium.UI.createWindow({
		navTintColor : 'white',
		extendEdges : [Ti.UI.EXTEND_EDGE_TOP],
		backgroundColor : '#F5F4F2',
		statusBarStyle : Titanium.UI.iPhone.StatusBar.LIGHT_CONTENT,
		layout : 'composite',
		tabBarHidden : true,
		rightNavButton : settingsButton,
		leftNavButton : tutorialButton
	});

	/*switch(volume) {
	case 1.0:
		self.rightNavButton = volumeButton4;
		break;
	case 0.7:
		self.rightNavButton = volumeButton3;
		break;
	case 0.4:
		self.rightNavButton = volumeButton2;
		break;
	case 0.0:
		self.rightNavButton = volumeButton1;
		break;
	}*/

	//self.add(loader);
	self.add(mainView);

	return self;
}

module.exports = MainWindow;
