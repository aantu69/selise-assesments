var accessToken = '';
let invoice = inv_rand();
$(document).ready(function() {
    alert('Working...');
    $("#bKash_button").click(function() {
        document.body.className = "loading";
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        url: "{!! route('token') !!}",
        type: 'POST',
        contentType: 'application/json',
        timeout: 30000,
        success: function(data) {
            console.log('got data from token  ..');
            console.log(JSON.stringify(data));
            accessToken = JSON.stringify(data);
        },
        error: function(error) {
            console.log(error.error);
        }
    });

    var paymentConfig = {
        createCheckoutURL: "{!! route('create-payment') !!}",
        executeCheckoutURL: "{!! route('execute-payment') !!}",
        queryPaymentURL: "{!! route('query-payment') !!}",
    };

    var paymentRequest;
    paymentRequest = {
        amount: $('.amount').text(),
        intent: 'sale',
    };

    bKash.init({
        paymentMode: 'checkout',
        paymentRequest: paymentRequest,
        createRequest: function(request) {
            console.log('=> createRequest (request) :: ');
            console.log(request);
            $.ajax({
                url: paymentConfig.createCheckoutURL + "?amount=" + paymentRequest
                    .amount + "&invoice=" + invoice,
                type: 'GET',
                contentType: 'application/json',
                timeout: 30000,
                success: function(data) {
                    document.body.className = "";
                    console.log('got data from create  ..');
                    console.log('data ::=>');
                    console.log(JSON.stringify(data));
                    var obj = JSON.parse(data);
                    if (data && obj.paymentID != null) {
                        paymentID = obj.paymentID;
                        bKash.create().onSuccess(obj);
                    } else {
                        console.log('error');
                        bKash.create().onError();
                    }
                },
                error: function(error) {
                    document.body.className = "";
                    var jsonObj = JSON.parse(JSON.stringify(error));
                    var errorCode = jsonObj['errorCode'];
                    var errorMessage = jsonObj['errorMessage'];
                    swal("Payment failed!", errorMessage, "error");
                    bKash.create().onError();
                }
            });
        },
        executeRequestOnAuthorization: function() {
            console.log('=> executeRequestOnAuthorization');
            $.ajax({
                url: paymentConfig.executeCheckoutURL + "?paymentID=" + paymentID,
                type: 'GET',
                contentType: 'application/json',
                timeout: 30000,
                success: function(data) {
                    document.body.className = "";
                    console.log('got data from execute  ..');
                    console.log('data ::=>');
                    console.log(JSON.stringify(data));
                    data = JSON.parse(data);
                    console.log('data ::=>');

                    if (data && data.paymentID != null) {
                        window.location.href = "{!! route('success') !!}";
                        // console.log(data);
                        // console.log('data ::=>');
                    } else {
                        var jsonObj = JSON.parse(JSON.stringify(data));
                        var errorCode = jsonObj['errorCode'];
                        var errorMessage = jsonObj['errorMessage'];
                        swal("Payment failed!", errorMessage, "error");
                        bKash.execute().onError();
                    }

                    // if (data && data.paymentID != null) {
                    //     alert('[SUCCESS] data : ' + JSON.stringify(data));
                    //     window.location.href = "{!! route('success') !!}";
                    // } else {
                    //     bKash.execute().onError();
                    // }
                },
                error: function() {
                    // bKash.execute().onError();

                    $.ajax({
                        url: paymentConfig.queryPaymentURL + "?paymentID=" +
                            paymentID,
                        type: 'GET',
                        contentType: 'application/json',
                        timeout: 30000,
                        success: function(data) {
                            document.body.className = "";
                            document.body.className = "";
                            data = JSON.parse(data);
                            if (data && data.paymentID != null) {
                                window.location.href =
                                    window.location.href =
                                    "{!! route('success') !!}";
                            } else {
                                var jsonObj = JSON.parse(JSON.stringify(
                                    data));
                                var errorCode = jsonObj['errorCode'];
                                var errorMessage = jsonObj[
                                    'errorMessage'];
                                swal("Payment failed!", errorMessage,
                                    "error");
                                bKash.execute().onError();
                            }
                        },
                        error: function(error) {
                            document.body.className = "";
                            var jsonObj = JSON.parse(JSON.stringify(
                                error));
                            var errorCode = jsonObj['errorCode'];
                            var errorMessage = jsonObj['errorMessage'];
                            swal("Payment failed!", errorMessage,
                                "error");
                            bKash.execute().onError();
                        }
                    });
                }
            });
        },
        onClose: function() {
            location.reload();
        }
    });
    // console.log("Right after init ");
});

function inv_rand() {
    return Math.random().toString(36).substr(2, 9);
}

function callReconfigure(val) {
    bKash.reconfigure(val);
}

function clickPayButton() {
    $("#bKash_button").trigger('click');
}