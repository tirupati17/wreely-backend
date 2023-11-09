/**
 */


jQuery(document).ready(function(){

	jQuery(document).on("click", ".deleteUser", function(){
		var userId = $(this).data("userid"),
			hitURL = baseURL + "deleteUser",
			currentRow = $(this);

		var confirmation = confirm("Are you sure to delete this user ?");

		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { userId : userId }
			}).done(function(data){
				console.log(data);
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("User successfully deleted"); }
				else if(data.status = false) { alert("User deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});

	jQuery(document).on("click", ".deleteEnquiry", function(){
		var enquiryId = $(this).val(),
			  hitURL = baseURL + "deleteEnquiry",
			  currentRow = $(this);

		var confirmation = confirm("Are you sure to delete this enquiry ?");
		if (confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { enquiryId : enquiryId }
			}).done(function(data){
				console.log(data);
				$('table#enquiry tr#'+enquiryId).remove();
				$("#modal-default").modal('hide');
				if (data.status = true) {
					alert("Enquiry successfully deleted");
				} else if(data.status = false) {
					alert("Enquiry deletion failed");
				} else {
					alert("Access denied..!");
			  }
			});
		}
	});

	jQuery(document).on("click", ".deleteMembershipType", function(){
		var membershipTypeId = $(this).val(),
				hitURL = baseURL + "deleteMembershipType",
				currentRow = $(this);

		var confirmation = confirm("Are you sure to delete this membership type ?");
		if (confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { membershipTypeId : membershipTypeId }
			}).done(function(data){
				if (data.status = true) {
					$('table#membershipType tr#'+'membership'+membershipTypeId).remove();
					$("#modal-membershipType").modal('hide');
				} else if(data.status = false) {
					alert("Membership type  deletion failed");
				} else {
					alert("Access denied..!");
				}
			});
		}
	});

jQuery(document).on("click", ".deleteSeat", function() {
	var seatId = $(this).val(),
			hitURL = baseURL + "deleteSeat",
			currentRow = $(this);

	var confirmation = confirm("Are you sure to delete this seat ?");
	if (confirmation)
	{
		jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { seatId : seatId }
				}).done(function(data){
					if (data.status = true) {
						console.log(seatId);
						$('table#seats tr#'+'seat'+seatId).remove();
						$("#modal-seat").modal('hide');
					} else if(data.status = false) {
						alert("Enquiry deletion failed");
					} else {
						alert("Access denied..!");
					}
				});
	}
});

jQuery(document).on("click", ".deleteSpace", function(){
	var spaceId = $(this).val(),
			hitURL = baseURL + "deleteSpace",
			currentRow = $(this);

	var confirmation = confirm("Are you sure want to empty this space ?");
	if (confirmation)
	{
		jQuery.ajax({
		type : "POST",
		dataType : "json",
		url : hitURL,
		data : { spaceId : spaceId }
		}).done(function(data){
			console.log(data);
			$('table#space tr#'+spaceId).remove();
			$("#modal-space").modal('hide');
			if (data.status = true) {
			} else if(data.status = false) {
				alert("Space deletion failed");
			} else {
				alert("Access denied..!");
			}
		});
	}
});

jQuery(document).on("click", ".deleteCompany", function(){
	var companyId = $(this).val(),
			hitURL = baseURL + "deleteCompany",
			currentRow = $(this);

	var confirmation = confirm("Are you sure want to delete this company ?");
	if (confirmation)
	{
		jQuery.ajax({
		type : "POST",
		dataType : "json",
		url : hitURL,
		data : { companyId : companyId }
		}).done(function(data){
			$('table#company tr#'+companyId).remove();
			$("#modal-company").modal('hide');
			if (data.status = true) {
			} else if(data.status = false) {
				alert("Company deletion failed");
			} else {
				alert("Access denied..!");
			}
		});
	}
});

jQuery(document).on("click", ".deleteMember", function(){
	var memberId = $(this).val(),
			hitURL = baseURL + "deleteMember",
			currentRow = $(this);

	var confirmation = confirm("Are you sure want to delete this member ?");
	if (confirmation)
	{
		jQuery.ajax({
		type : "POST",
		dataType : "json",
		url : hitURL,
		data : { memberId : memberId }
		}).done(function(data){
			$('table#member tr#'+memberId).remove();
			$("#modal-member").modal('hide');
			if (data.status = true) {
			} else if(data.status = false) {
				alert("Member deletion failed");
			} else {
				alert("Access denied..!");
			}
		});
	}
});
	jQuery(document).on("click", ".searchList", function(){

	});

});
