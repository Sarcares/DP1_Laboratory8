/**
 * This object allows to manage an AJAX request.
 *
 * @param url - The url that you want to contac
 * @param callback - The function that you want to trigger when the request will be completed.
 * @constructor
 */
function AJAXRequestObject(url, callback)
{
    function ajaxRequest() {
        try { // Non IE Browser?
            var request = new XMLHttpRequest();
        }
        catch(e1){ // No
            try { // IE 6+?
                request = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e2){ // No
                try { // IE 5?
                    request = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch(e3){ // No AJAX Support
                    request = false;
                }
            }
        }
        return request;
    }

    function processRequest () {
        if (req.readyState == 4) {
            if (req.status == 200|| req.status==0) {
                if (callback)
                    callback(req.responseText);
            }
        }
    }

    var req = ajaxRequest();
    req.onreadystatechange = processRequest;

    this.doGet =
        function() {
            req.open("GET", url, true);
            req.send();
        }
    this.doPost =
        function(body) {
            req.open("POST", url, true);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.send(body);
        }
}

/**
 * This function creates an object for send AJAX requests.
 * @returns {XMLHttpRequest}
 */
function ajaxRequest() {
    try { // Non IE Browser?
        var request = new XMLHttpRequest()
    }
    catch(e1) { // No
        try { // IE 6+?
            request = new ActiveXObject("Msxml2.XMLHTTP")
        }
        catch(e2) { // No
            try { // IE 5?
                request = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch(e3) { // No AJAX Support
                request = false
            }
        }
    }
    return request
}