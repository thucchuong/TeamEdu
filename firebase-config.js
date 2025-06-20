import * as firebase from './assets/modules/firebase/firebase-app.js'; 

var config = {
	apiKey: "xxxx",
	authDomain: "xxx-xx-xxx.firebaseapp.com",
	databaseURL: "https://xx-xx-xx.firebaseio.com",
	projectId: "fcm-xx-xx",
	storageBucket: "xx-xxx-xxxx.appspot.com",
	messagingSenderId: "xxxxxxx",
	appId: "xxxxxxxxxxxxxxxxx"
};

firebase.initializeApp(config);
var notification = [];
var icon = '';
