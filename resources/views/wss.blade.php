<html>
<body>

</body>
{{--<script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script>--}}
{{--<script>--}}
{{--    const { io } = require("socket.io-client");--}}
{{--    const socket = io();--}}
{{--</script>--}}

<script type="module">
    import { io } from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";
    const options = {
        // autoConnect: false,
        auth: {
            token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjdiNjVlZTgxZmFhMjc0ZDM3MTU3NDRiOWYwN2MyNmU3MjYzNjY5Y2ZjNDg0YTcxMWFiM2IyOWE4YjE3ODM0YTFmNGU4OWVhMTZlOWI1ZWYiLCJpYXQiOjE2NDQ4NjUzMDMuMjIwNTY4LCJuYmYiOjE2NDQ4NjUzMDMuMjIwNTc4LCJleHAiOjE2NzY0MDEzMDMuMTg0OTU5LCJzdWIiOiIyNyIsInNjb3BlcyI6W119.ctW4cgY0AfXLiLctfZo2gAhNmc-Az0_h0QYYK0GPc5_NgFkUm_w7N0Vpv0VoGULYSXHb4OnFJ87Qirs02Iq8EqQcp4HwhTlwTIxsMdBku2Y1n-e7SAwnWd3ChG0tzO-WtLMJUsHkTpiU2JFmP71X8WI879Gc0-BCsB3THHtWTlfWPnVx51PWhR138XvA1DbbPVYobJFwfvammZJ14WidVZ-qMTFcSHf0-B-LYD3Ewp9OFqJ-oyivgIFwfgKk7xHqh-bAP0x3NISCbi65a7i0WcwnHgMJjMXOw0_z1JN7DpII-POi45gGQEbWkTUxKLazks1xh_Msi8CDEelPtg0iGAF9eOEm2pe8K2rOlituCuMPjFYEQaywcoNBRj4yJjIoblDXUbIjMMjo9mzUkIXwAJp3ffJmFG60O0bmGNQ8WgoKTZtxuG1y3aLomKec8L6ME9WHul_6Qkp_TIjyj9So0QErFD6bYtw0_O3Nxb6rKYtPhoXGmqt4NctVfqK59xdMGA3cOm7Sycq_cb4wO-H1K4MRqSDvrCNbMpxr7VGReK6TpFBU5918kx-Og_W-DM865SEyd5gnUFK0OqG-Mhpq9HtA94c-9AZrEOy0dIxBj50hON-c6m7StCjKX1jWHkTEEXAxjtB96TmpAtrSLkMTXUgo0tkEuhSLZ46puPdLSBY',
        },
    }
    const address = "https://notifications.loveread.staj.fun/notifications";
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


