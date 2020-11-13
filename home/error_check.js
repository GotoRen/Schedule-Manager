function check () {

    var begin = document.loginCheck.begin.value;
    var end = document.loginCheck.end.value;

    var errorMessage = document.getElementById("errorMessage");
   
    // エラー処理: 開始日時が終了日時よりも後日だった場合、エラーにする
    if (begin > end) {
        errorMessage.innerHTML = "日時が不正確です。";
        document.loginCheck.end.focus();
        return false;
    }

    return true;
}   