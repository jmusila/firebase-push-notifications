/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
   apiKey: "AIzaSyChzbmyYbSdof4BovRzYquLEZKTV8-L58k",
   authDomain: "push-notificatios-a0853.firebaseapp.com",
   databaseURL: "https://push-notificatios-a0853.firebaseio.com",
   projectId: "push-notificatios-a0853",
   storageBucket: "push-notificatios-a0853.appspot.com",
   messagingSenderId: "182062638208",
   appId: "1:182062638208:web:428dcb0821563203ba7265"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});