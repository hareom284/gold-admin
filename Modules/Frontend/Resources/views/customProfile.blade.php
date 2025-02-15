@extends('frontend::layouts.master')
@section('content')
<div class="section-spacing-bottom">
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-3 ps-md-0 ps-3">
                <ul class="nav nav-tabs flex-column gap-4">
                    <a class="nav-link p-3 text-center d-flex align-items-center gap-3"  href="{{route('home')}}">
                        <i class="ph-fill ph-arrow-u-up-left fs-4 text-white"></i><h6 class="m-0">Back to Home</h6>
                    </a>
                    <li class="nav-item">
                        <a class="nav-link active p-3 text-center d-flex align-items-center gap-3" data-bs-toggle="pill" href="#editProfile">
                            <i class="ph-fill ph-gear fs-4 text-white"></i><h6 class="m-0">Profile Setting</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-3 text-center d-flex align-items-center gap-3" data-bs-toggle="pill" href="#subscription">
                            <i class="ph-fill ph-cardholder fs-4 text-white"></i><h6 class="m-0">Subscription</h6>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-3 text-center d-flex align-items-center gap-3" data-bs-toggle="pill" href="#paymentLog">
                            <i class="ph-fill ph-credit-card fs-4 text-white"></i><h6 class="m-0">Payment Log</h6>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-5">
                <div class="tab-content">
                    <div class="tab-pane active fade show" id="editProfile" role="tabpanel">
                        <div class="card user-login-card p-5">
                            <div class="edit-profile-content">
                                <div class="edit-profile-details">
                                    <div class=" rounded p-5">
                                        <h6 class="mb-3">{{__('frontend.profiles_details')}}</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="select-profile-card text-center position-relative">
                                                    <!-- Profile Image -->
                                                    <img id="profileImage" src="{{setBaseUrlWithFileName($user->file_url) ?? setDefaultImage()}}" class="img-fluid rounded-circle object-cover"
                                                        alt="select-profile-image" style="cursor: pointer; width: 150px; height: 150px;">

                                                    <!-- Hidden file input -->
                                                    <input type="file" id="profileImageInput" class="d-none" accept="image/*" onchange="previewImage(event)">

                                                    <!-- Pencil icon -->
                                                    <i class="ph ph-pencil pencil-icon" onclick="triggerFileInput()"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-md-0 mt-4">
                                                <form id="editProfileDetail">
                                                    @csrf
                                                    <div class="input-group mb-3">
                                                        <span class="input-style-text input-group-text px-0"><i class="ph ph-user"></i></span>
                                                        <input type="text" name="first_name" class="form-control input-style-box" value="{{ $user->first_name }}" placeholder="{{__('frontend.enter_fname')}}" >
                                                        <div class="invalid-feedback" id="first_name_error">First Name field is required</div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-user"></i></span>
                                                        <input type="text" name="last_name" class="form-control input-style-box" value="{{ $user->last_name }}" placeholder="{{__('frontend.enter_lname')}}">
                                                        <div class="invalid-feedback" id="last_name_error">Last Name field is required</div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-envelope"></i></span>
                                                        <input type="email" name="email" class="form-control input-style-box" value="{{ $user->email }}" @if ($user->login== 'google') readonly @endif>
                                                        <div class="invalid-feedback" id="email_error">Email is required</div>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text input-style-text px-0"><i class="ph ph-phone"></i></span>
                                                        <input type="tel" class="form-control input-style-box" value="{{ $user->mobile }}" id="mobileInput"  @if ($user->login== 'otp') readonly @endif>
                                                        <div class="invalid-feedback" id="mobile_error">Mobile number is required</div>
                                                    </div>

                                                    <div class="d-grid gap-2 col-12">
                                                        <button type="button" id="updateProfileBtn" class="btn btn-primary mt-5">{{__('frontend.update')}}</button>
                                                    </div>
                                                </form>
                                                <hr class="my-5" style="color:white;height:2px">
                                                <div class="d-grid gap-2 col-12 mt-3">
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">{{__('frontend.delete_account')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="subscription" role="tabpanel">
                        <div class="card  p-5">
                            <div class="card-body ">
                                <div class="edit-profile-content mb-5">
                                    <h5>Subscription</h5>
                                    <small class="text-muted">Plan Details</small>
                                </div>
                                <div class="form-check align-items-center col-12 col-md-6 mb-2" style="display: flex !important">
                                    <input class="form-check-input me-2" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <label class="form-check-label w-100" for="flexRadioDefault1">
                                        <div class="list-group">
                                            <div class="list-group-item  p-4 ">
                                                <div>
                                                    <span>Monthly Plan</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <span class="text-bold text-white">31 Days</span>
                                                    <span class="text-bold text-white">3,000 MMK</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                  </div>
                                  <div class="form-check align-items-center col-12 col-md-6 " style="display: flex !important">
                                    <input class="form-check-input me-2" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                    <label class="form-check-label w-100" for="flexRadioDefault2">
                                        <div class="list-group">
                                            <div class="list-group-item  p-4 ">
                                                <div>
                                                    <span>Yearly Plan</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <span class="text-bold text-white">365 Days</span>
                                                    <span class="text-bold text-white">35,000 MMK</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="d-grid gap-2 w-50 mt-3">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Subscribe</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="paymentLog" role="tabpanel">
                        <div class="card p-5">
                            <div class="card-body">
                                <div class="edit-profile-content mb-5">
                                    <h5>Payment Logs</h5>
                                    <small class="text-muted">Logs Details</small>
                                </div>
                                <div class="list-group">
                                    <div class="list-group-item w-50 border-top-0 border-end-0 border-start-0 p-0 pb-3 mb-3 rounded-0">
                                        <div>
                                            <span>ID: 002461</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-white">Monthly Plan</span>
                                            <span>01/02/2024</span>
                                            <span>3,000 MMKs</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group">
                                    <div class="list-group-item w-50 border-top-0 border-end-0 border-start-0 p-0 pb-3 mb-3 rounded-0">
                                        <div>
                                            <span>ID: 002461</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-white">Monthly Plan</span>
                                            <span>01/02/2024</span>
                                            <span>3,000 MMKs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-acoount-card">
        <div class="modal-content position-relative">
            <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
            <i class="ph ph-x text-white fw-bold align-middle"></i>
            </button>
        <div class="modal-body modal-acoount-info text-center">
            <img src="{{ asset('img/web-img/remove_icon.png') }}" alt="delete image">
            <h4 class="mt-5 pt-4">{{__('frontend.permanent_delete')}}</h4>
            <p class="pb-4 mb-0">{{__('frontend.permanent_deleted')}}</p>
            <div class="d-flex justify-content-center gap-3 mt-4 pt-3">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{__('frontend.cancel')}}</button>
                <button type="button" class="btn btn-dark" onclick="proceedToDeleteAccount()">{{__('frontend.proceed')}}</button>
            </div>
        </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>


<script>


    var input = document.querySelector("#mobileInput");
    var iti = window.intlTelInput(input, {
        initialCountry: "mm",  // Automatically detect user's country
        separateDialCode: true,  // Show the country code separately
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"  // To handle number formatting
    });

    function proceedToDeleteAccount() {
        fetch(`${baseUrl}/api/delete-account`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                $('#deleteAccountModal').modal('hide');

                $('#proceedAccountModal').modal('show');

                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = '{{ route('home') }}';
                }, 3000);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    }

function triggerProfileFileInput() {
    document.getElementById('profileFileImageInput').click();
}

// Function to preview the selected image
function previewProfileImage(event) {
    const reader = new FileReader();
    const fileInput = event.target;

    reader.onload = function() {
        const previewImage = document.getElementById('profile_image');
        previewImage.src = reader.result; // Update the image preview
    };

    reader.readAsDataURL(fileInput.files[0]);
}


      function triggerFileInput() {
        document.getElementById('profileImageInput').click();
    }
    function previewImage(event) {
        const image = document.getElementById('profileImage');
        image.src = URL.createObjectURL(event.target.files[0]);
    }

    document.getElementById('profileImage').addEventListener('click', triggerFileInput);
    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

    document.addEventListener('DOMContentLoaded', function () {

        $(document).ready(function() {

            $('#updateProfileBtn').on('click', function(e) {
                e.preventDefault();

                $('.invalid-feedback').hide();
                $('input').removeClass('is-invalid');

                let valid = true;

                const fieldsToValidate = [
                    {
                        name: 'first_name',
                        errorElement: '#first_name_error'
                    },
                    {
                        name: 'last_name',
                        errorElement: '#last_name_error'
                    },

                ];

                fieldsToValidate.forEach(field => {
                    const value = $(`input[name="${field.name}"]`).val().trim();
                    if (!value) {
                        $(field.errorElement).show();
                        $(`input[name="${field.name}"]`).addClass('is-invalid');
                        valid = false;
                    }
                });

                const mobileInput = $('#mobileInput');

                    const mobileValue = mobileInput.val().trim();
                    if (!mobileValue) {
                        $('#mobileInput').addClass('is-invalid');
                        $('#mobile_error').show().text('Mobile number is required');
                        valid = false;
                    } else {
                        $('#mobile_error').hide();
                    }

                if (!valid) {
                    return;
                }

                var number = iti.getNumber()


                var formData = new FormData($('#editProfileDetail')[0]);

                formData.append('mobile', number);

                var imageFile = $('#profileImageInput')[0].files[0];
                if (imageFile) {
                    formData.append('file_url', imageFile);
                }

                var $btn = $(this);
                $btn.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `${baseUrl}/api/update-profile`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': 'Bearer ' + '{{ auth()->user()->api_token }}'
                    },
                    success: function(response) {
                        if (response.status === true) {
                            $('input[name="first_name"]').val(response.data.first_name);
                            $('input[name="last_name"]').val(response.data.last_name);
                            $('input[name="email"]').val(response.data.email);
                            $('input[name="mobile"]').val(response.data.mobile);
                            $('input[name="date_of_birth"]').val(response.data.date_of_birth);

                          //  const image = document.getElementById('profileImage');
                         //   image.src =setBaseUrlWithFileName(response.data.file_url);

                            $('input[name="gender"][value="' + response.data.gender + '"]').prop('checked', true);

                            window.successSnackbar(response.message)

                            $btn.prop('disabled', false).text('Update');

                        } else {
                            window.successSnackbar('Error updating profile.')
                            $btn.prop('disabled', false).text('Update');

                        }
                    },
                    error: function(xhr, status, error) {
                  var response = JSON.parse(xhr.responseText);

                  if (response.message) {

                      window.successSnackbar(response.message);
                  } else if (response.errors && response.errors.mobile) {

                      window.successSnackbar(response.errors.mobile[0]);
                  }

                  $btn.prop('disabled', false).text('Update');
                 }
                });
            });
        });

        $(document).ready(function () {
            const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

            const apiUrl = `${baseUrl}/api/save-userprofile`;

    $("#ProfileDetail").on("submit", function (e) {
        e.preventDefault();
        let isValid = true;

        const nameField = $("#profile_first_name");
        if (nameField.val().trim() === "") {
            nameField.addClass("is-invalid");
            isValid = false;
        } else {
            nameField.removeClass("is-invalid");
        }

        const selectedImage = $('input[name="profile_image"]:checked').val();
        const profileId = $("#profile_id").val();

         var formData = new FormData();

         if (!isValid) return;

         formData.append('id', profileId);
         formData.append('avatar', selectedImage);
         formData.append('name', nameField.val());

         var imageFile = $('#profileFileImageInput')[0].files[0];
         if (imageFile) {
             formData.append('file_url', imageFile);
         }
                // Make an AJAX request to save the profile
                $.ajax({
                    url: apiUrl, // Replace with your actual API endpoint
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        $("#profileList").empty();

                        response.data.forEach(function (profile) {
                            let profileHtml = '';

                            if (profile.is_active == 1) {
                                profileHtml = `
                                <div class="col">
                                    <div class="card bg-body profil-card border border-primary" >
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            } else {
                                profileHtml = `
                                <div class="col" >
                                    <div class="card bg-body profil-card" onclick="SelectProfile(${profile.id})" >
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image" >
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            }

                            $("#profileList").append(profileHtml);
                        });

                        // Append the "Add User" card at the end of the profile list
                        const addUserHtml = `
                            <div class="col">
                                <div class="card profil-card cursor-pointer" data-bs-toggle="modal"  data-type="add" data-bs-target="#selectProfileModal">
                                    <div class="card-body bg-body rounded text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="profile-card-add-user bg-dark">
                                            <i class="ph ph-plus"></i>
                                        </div>
                                        <h5 class="mt-3 mb-0 font-size-18">Add Profile</h5>
                                    </div>
                                </div>
                            </div>`;

                        $("#profileList").append(addUserHtml);
                        window.successSnackbar(response.message)
                        // Close the modal
                        $("#selectProfileModal").modal('hide');
                    },
                    error: function (xhr, status, error) {
                        if(xhr.status){
                            window.successSnackbar(xhr.responseJSON.error)

                        }else{
                            window.successSnackbar('Something went wrong!')
                        }
                        $("#selectProfileModal").modal('hide');

                    }
                });

                $("#profile_id").val("");
            });

            // Reset validation feedback when modal is closed
            $('#selectProfileModal').on('hidden.bs.modal', function () {
                $("#ProfileDetail")[0].reset();
                $(".is-invalid").removeClass("is-invalid");

            });
        });

    });

    function editProfile(id) {

        const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
        const apiUrl = `${baseUrl}/api/get-userprofile/${id}`;

        fetch(apiUrl, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {

            document.getElementById('profile_id').value = response.data.id;
            document.getElementById('profile_first_name').value = response.data.name;
            const profileImageValue = response.data.avatar; // Get the profile image value from the API
            const modalImage = document.getElementById('profile_image');
            document.getElementById('profile_image_value').value = profileImageValue;
            modalImage.setAttribute('src', profileImageValue);
            $('#selectProfileModal').modal('show');


        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    function SelectProfile(id){

        const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
        const apiUrl = `${baseUrl}/api/select-userprofile/${id}`;

        fetch(apiUrl, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                 throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(response => {
            // window.successSnackbar(response.message)
            $("#profileList").empty();

                response.data.forEach(function (profile) {
                            let profileHtml = '';

                            if(profile.is_active == 1) {
                                profileHtml = `
                                <div class="col">
                                    <div class="card bg-body profil-card border border-primary">
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            } else {
                                profileHtml = `
                                <div class="col" >
                                    <div class="card bg-body profil-card"  onclick="SelectProfile(${profile.id})">
                                        <div class="card-body  rounded text-center">
                                            <div class="profile-card-image">
                                                <img id="profile_image_${profile.id}" src="${profile.avatar}" alt="profile-image">
                                            </div>
                                            <h5 class="mt-3 mb-4 font-size-18" id="profile_name_${profile.id}">${profile.name}</h5>
                                            <button class="btn p-0 h6 mb-0" data-bs-toggle="modal" data-type="update" data-bs-target="#selectProfileModal">
                                                <span class="d-flex align-items-center gap-2" onclick="event.stopPropagation(); editProfile(${profile.id})">
                                                    <span><i class="ph ph-pencil-simple-line"></i></span>
                                                    <span>Edit</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            }

                            $("#profileList").append(profileHtml);
                });


                // Append the "Add User" card at the end of the profile list
                const addUserHtml = `
                    <div class="col">
                        <div class="card profil-card cursor-pointer" data-bs-toggle="modal"  data-type="add" data-bs-target="#selectProfileModal">
                            <div class="card-body bg-body rounded text-center d-flex flex-column align-items-center justify-content-center">
                                <div class="profile-card-add-user bg-dark">
                                    <i class="ph ph-plus"></i>
                                </div>
                                <h5 class="mt-3 mb-0 font-size-18">Add Profile</h5>
                            </div>
                        </div>
                    </div>`;

                $("#profileList").append(addUserHtml);

        })
        .catch(error => {
            window.successSnackbar(error)

        });

    }


    document.addEventListener('DOMContentLoaded', function () {
        const selectProfileModal = document.getElementById('selectProfileModal');

        selectProfileModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const dataType = button.getAttribute('data-type');
            const updateButton = document.getElementById('update-profile');

            if (dataType === 'add') {
                $("#ProfileDetail")[0].reset();
                $("#profile_id").val('');
                updateButton.textContent = '{{__('messages.add')}}'; // Change to "Add"

            } else {

                updateButton.textContent = '{{__('frontend.update')}}'; // Default "Update"

            }
        });


    });

</script>
@endsection
