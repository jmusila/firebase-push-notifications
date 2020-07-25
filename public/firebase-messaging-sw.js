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
    apiKey: '{{env('FIREBASE_API_KEY')}}',
    authDomain: '{{env('FIREBASE_AUTH_DOMAIN')}}',
    databaseURL:'{{env('FIREBASE_DATABASE_URL')}}',
    projectId: '{{env('FIREBASE_PROJECT_ID')}}',
    storageBucket: '{{env('FIREBASE_STORAGE_BUCKET')}}',
    messagingSenderId: '{{env('FIREBASE_MESSAGING_SENDER_ID')}}',
    appId: '{{env('FIREBASE_APP_ID')}}',
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