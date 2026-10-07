jQuery(document).ready(function ($) {
  console.log("main.js file");

  const fomrs = [
    "#signup form",
    "#login form",
    "#verify-email form",
    "#forget-password-or-username form",
    "#set-new-password form",
    "#edit form",
  ];

  $(fomrs.join(", "))
    .find("input")
    .each(function () {
      $(this).on("input", function () {
        // $(this).prev().css({ "color": "black" })
        $(this).next().text("");
        $(this).css({ border: "1px solid black" });
        console.log($(this));
      });
    });

  $("#signup form").on("submit", function (e) {
    submitForm.call(this, e, "signup", "POST");
  });
  $("#login form").on("submit", function (e) {
    submitForm.call(this, e, "login", "POST");
  });
  $("#verify-email form").on("submit", function (e) {
    submitForm.call(this, e, "verify-email", "POST");
  });
  $("#verify-email-2 form").on("submit", function (e) {
    submitForm.call(this, e, "verify-email", "POST");
  });
  $("#forget-password-or-username form").on("submit", function (e) {
    submitForm.call(this, e, "forget", "POST");
  });
  $("#set-new-password form").on("submit", function (e) {
    submitForm.call(this, e, "set-new-password", "POST");
  });

  $("#edit form").on("submit", function (e) {
    submitForm.call(this, e, "edit-profile", "PUT");
  });

  $("#delete form").on("submit", function (e) {
    submitForm.call(this, e, "delete-profile", "DELETE");
  });

  function submitForm(e, action, type) {
    e.preventDefault();

    $(this).find("p#message").text("");

    let isVerified = true;

    // const data = new FormData(this)
    const data = $(this).serializeArray();

    $(this)
      .find("input")
      .each(function () {
        if ($(this).val() === "") {
          // $(this).prev().css("color", "red")
          $(this)
            .next("p")
            .text(
              `${($(this).attr("name").charAt(0).toUpperCase() + $(this).attr("name").slice(1)).replace(/-/g, " ")} field  can't be empty.`,
            )
            .css({
              color: "red",
              "font-size": "0.75vw",
              margin: "0.125vw 0 0.125vw 0",
            });
          $(this).css({ border: "1px solid red" });

          isVerified = false;
        }
      });

    let body = {};

    $.each(data, function () {
      body[this.name.replace(/-/g, "_")] = this.value;
    });

    if (type == "DELETE") {
      if (body["delete"] == "false") {
        window.location.href =
          window.location.origin + "/arshdeepsingh/dashboard";
        return;
      }

      if (body["delete"] == undefined) {
        return;
      }
    }

    if (body["password"] && body["confirm_password"]) {
      if (body["password"].length < 8) {
        $("input#password")
          .next("p")
          .text("Password must have at least 8 characters.")
          .css({
            color: "red",
            "font-size": "0.75vw",
            margin: "0.125vw 0 0.125vw 0",
          });
        $("input#password").css({ border: "1px solid red" });
        isVerified = false;
      }

      if (body["password"] == body["confirm_password"]) {
        console.log("Passwords match.");
      } else {
        $(this)
          .find("p#message")
          .text("Passwords do not match.")
          .css("color", "red");

        isVerified = false;
      }
    }

    if (!isVerified) return;

    $.ajax({
      url: ajax.endpoint + "?action=auth",
      type: type,
      data: JSON.stringify({ data: body, action: action }),
      success: (res, status) => {
        console.log(res);
        console.log(status);

        if (res.success == false && !res?.data?.message) {
          if (res?.data?.expired) {
            console.log(res?.data?.expired);
            $(this).find('button[type="submit"]').text("Regenerate");
            $(this).find('input[name="regenerate_otp"]').attr("value", "true");
          }

          for (let key in res.data) {
            $(this)
              .find("input")
              .each(function () {
                if ($(this).attr("name") == key) {
                  // $(this).prev().css("color", "red")
                  $(this).next().text(res.data[key]).css({
                    color: "red",
                    "font-size": "0.75vw",
                    margin: "0.125vw 0 0.125vw 0",
                  });
                  $(this).css({ border: "1px solid red" });
                }
              });
          }
        }
        if (res.success == false && res?.data?.message) {
          let color = "red";

          if (action == "verify-email") {
            color = "green";

            $(this).find('button[type="submit"]').text("Verify");
            $(this).find('input[name="regenerate_otp"]').attr("value", "false");
          }

          if (action == "forget") {
            color = "green";
          }

          $(this)
            .find("p#message")
            .text(res?.data?.message)
            .css("color", color);
        }

        if (res.success == true) {
          $(this)
            .find("p#message")
            .text(res?.data?.message)
            .css("color", "green");
          window.location.href = res?.data.redirect;
        }
      },
      error: (xhr, status, error) => {
        console.log(xhr.statusText);
        console.log(xhr.status);
        console.log(error);
        console.log(status);
      },
    });
  }

  $("#forget-password-or-username form select").on("change", function () {
    if ($(this).val() == "password") {
      $("#forget-password-or-username button").text("Reset");
    } else {
      $("#forget-password-or-username button").text("Find");
    }
  });
});
