<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dogs</title>
</head>
<body>
<div id="output">

</div>
<input type="text" placeholder="Enter command" id="command" autofocus="autofocus">
<script>
    let output = document.getElementById('output');
    let command = document.getElementById('command');

    command.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            let commandString = command.value;
            command.value = '';
            fetch('/api.php', {
                method: 'POST',
                cache: 'no-cache',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'command': commandString
                })
            }).then((response) => {
                return response.json();
            }).then((data) => {
                output.innerHTML += data.output + '<br>';
            })
        }
    })
</script>
</body>
</html>
