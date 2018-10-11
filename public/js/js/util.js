var M = {};

function Alert(msg) {
	// if(M.dialog1){
	// 		return M.dialog1.show();
	// 	}
		M.dialog1 = jqueryAlert({
			'content' : msg,
			'closeTime' : 2000,
			'animateType': ''
		});
}