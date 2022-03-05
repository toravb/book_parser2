<html>
<body>

</body>
{{--<script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script>--}}
{{--<script>--}}
{{--    const { io } = require("socket.io-client");--}}
{{--    const socket = io();--}}
{{--</script>--}}

<script type="module">

    // let user = {
    //     email: 'anton4@gmail.com',
    //     password: '123456789'
    // };
    //
    // let response = await fetch('https://loveread.webnauts.pw/api/register', {
    //     method: 'POST',
    //     headers: {
    //         'Sec-Fetch-Mode': 'cors',
    //         'Accept' : 'application/json',
    //         'Content-Type': 'application/json'
    //     },
    //     body: JSON.stringify(user)
    // });
    //
    // let result = await response.json();
    // console.log(result)

    import { io } from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";
    const options = {
        // autoConnect: false,
        auth: {
            token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMDIxMGZiMjQ0ODEyYzcwMzUzNjk5NTJiYjU1NzY5MTIwMWMyYzkxOWU3OWY3MTE4ZWJlYzAxZjY4YmY0MDFiMzdmMjA3MDk1ZDdmOTIxNWQiLCJpYXQiOjE2NDY0OTM4MDYuNTAxMzEyLCJuYmYiOjE2NDY0OTM4MDYuNTAxMzE1LCJleHAiOjE2NzgwMjk4MDYuNDk0Mzc5LCJzdWIiOiIxMCIsInNjb3BlcyI6W119.k9ZnEU9NnOQ5yUzAO9T1pfK2pecaj6SzrbPsbF3bAcLCPfmPdIIKVKLd_r5dOl8QIW_W2Z5Al6yUuPFmKmriwU-RtFg82YWGEfEK_31X_Cweac6JpCkl1sdHFYykPzRHWyOjMONLk1gW2zel1u2Zcw6bOxIx7jTi30IcKg-qVOdPJ5GXyMBDbznEWpx_FxBwQd9JYtOGpIu2sjGX2yfoUMXHoIJOadjg0u9ZJ5cS1NybV9BhIb5bX_4Porg6oxNdACdzrol6FI9gWt0JVykgrMXl44y89QobD4t3qEtkTXDhg26Io-K7Sgq_3fVoH5IWeKt9TOtoiIqdq4p74WYnVBvhOsoWS-qY4qKNw1HFKKguEorUhpPPXnLmY3YqHg70PBCAS9m7Q2AS6Ku1wSsQk2QcTtf6lo4XG_3Nnwffs-rQyY0SwzNTOlVwEPfn8QTXb48IhZHXMFcRXNNWi_D_DxzJYcm96g8KFMLoDomC20gWx0mo3w8_nGXw-oMehjA9JxJz6EpP0Zhuq-yIG5-lUupe1gledx9vf0hVQ2KlB_0Tfxt7KVC1JxR7wW85WlFu9rMQuNl2gXzcmGJ2QdEJA521-spwHga63rrMDGR2t_f9qUP5MsxmsFpwLLtYUGthdly91e1Gj7VpueUQorUs0FSBzxAdEKyF8mAfBzDOBAo',
        },
    }
    const address = "https://notifications.loveread.webnauts.pro/notifications";
    // const socket = io({  auth: {    token: "abcd"  }});
    const socket = io(address, options);
    socket.on("connect", () => {
        console.log('Connected')
        console.log(socket.id)
    });

</script>
<script>

</script>
</html>


