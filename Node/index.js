const express = require('express');
const app = express();
const service = require('http').createServer(app);
// const https = require('https');
// const fs = require('fs');
// const privateKey  = fs.readFileSync('../.well-known/pki-validation/private.key', 'utf8');
// const certificate = fs.readFileSync('../.well-known/pki-validation/certificate.crt', 'utf8');
// const credentials = {key: privateKey, cert: certificate};
// const service = https.createServer(credentials, app);

const mysql = require('mysql');
const port = process.env.PORT || 5000;
const client = '';
const dbc = {
  host: "localhost",
  user: "root",
  password: "",
  database: "skripsi"
};
const db = mysql.createConnection(dbc);
app.get('/', function(req, res) {
  res.sendFile(__dirname + '/index.html');
});

db.connect(function (err){
	if(err){
		if (err) throw err;
	}else{
		console.log(`Connected Database: ${dbc.database}`);
	}
});

var UClients = {},
		myid = 0;
const io = require('socket.io')(service);
io.on('connection', function(socket){
	// socket.emit('get_data', socket.id);
	socket.on('join', function(data){
		UClients[socket.id] = data;
		console.log('Connected', socket.id);
		if(data.id){
			myid = data.id;
			db.query(`UPDATE users SET onlineUsers = 1 WHERE idUsers = ${data.id}`, function(err){
				if(err){

				}else{
					socket.broadcast.emit('post2users', {
						data: UClients,
						type: 'join'
					});
				}
			});
		}
	})
	socket.on('disconnect', function() {
		const data = UClients[socket.id];
		console.log('Disconnect', socket.id);
		if(typeof UClients[socket.id] !== 'undefined' && typeof data.id !== 'undefined'){
			delete UClients[socket.id];
			db.query(`UPDATE users SET onlineUsers = 0 WHERE idUsers = ${data.id}`, function(err){
				if(err){

				}else{
					socket.broadcast.emit('post2users', {
						data: UClients,
						type: 'out'
					});
				}
			});
		}
	});
});

service.listen(port, (e) => {
	console.log(`Listening Port: ${port}`)
});