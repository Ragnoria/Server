<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Laravel</title>
    </head>
    <body>
    <pre id="log"></pre>
    <script>
        // Create WebSocket connection.
        const socket = new WebSocket("{{ config('websockets.protocol') }}://{{ config('websockets.host') }}:{{ config('websockets.port') }}");

        // Connection opened
        socket.addEventListener("open", (event) => {
            document.querySelector('#log').innerText += 'Connection established \r\n'
            socket.send("Hello Server!");
        });

        // Listen for messages
        socket.addEventListener("message", (event) => {
            document.querySelector('#log').innerText += "Message from server " + JSON.stringify(event.data) + "\r\n"
        });

        // Connection opened
        socket.addEventListener("close", (event) => {
            document.querySelector('#log').innerText += 'Connection closed \r\n'
        });
    </script>
    </body>
</html>
