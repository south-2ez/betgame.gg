<template>
<span>
    <span class="float-right edit-profile-cog" @click="openEditProfileWindow">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown" :class="isOpen == true ? 'open' : ''">
                    <a href="#" class="dropdown-toggle edit-profile-dropdown small" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" @click.prevent="editPassword">Change Password</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="#" @click.prevent="deactivateAccount">Deactivate Account</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </span>

    <!-- Modal Voucher Code-->
    <div class="modal fade" id="modal-resetpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Reset Your Password
                        <span id="user-name" style="font-weight: bold;color: #820804"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="password-form" autocomplete="off" action method="POST" role="form">
                        <div class="form-group code" :class="passwordMatch === false ? 'has-error' : ''">
                            <label for="password" class="control-label">Password:</label>
                            <input id="password" name="password" type="password" placeholder="Password" class="form-control" v-model="password" />
                        </div>
                        <div class="form-group code" :class="passwordMatch === false ? 'has-error' : ''">
                            <label for="password" class="control-label">Confirm Password:</label>
                            <input id="password" name="password" type="password" placeholder="Password" class="form-control" v-model="confirmPassword" />
                        </div>
                        <div class="alert alert-danger" role="alert" v-show="!passwordMatch">{{ changePassErrorText }}</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit_password" @click.prevent="changePassword" data-progress-text="Saving ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deactivate Account-->
    <div class="modal fade" id="modal-deactivate-account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Deactivate Account
                        <span id="user-name" style="font-weight: bold;color: #820804"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="password-form" autocomplete="off" action method="POST" role="form">
                        <div class="form-group code" :class="passwordMatch === false ? 'has-error' : ''">
                            <label for="password" class="control-label">Reason For Deactivating:</label>
                            <select class="form-control" v-model="reason" @change="selectReason">
                                <option disabled selected>Select reason for deactivating</option>
                                <option>I have another account</option>
                                <option>I am not interested anymore</option>
                                <option>I am switching to another site/application</option>
                                <option>I don't understand how to use the application</option>
                                <option>Security issues</option>
                                <option>I don't trust the site</option>
                                <option>Others</option>
                            </select>

                            <textarea v-show="reason == 'Others'" v-model="reason_text" class="form-control margin-top-20" rows="2" placeholder="Please enter your reason"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Please type the word <span class="text-yellow">DEACTIVATE</span>:</label>
                            <input name="deactivate" type="text" placeholder="Deactivate" class="form-control" v-model="deactivate" />
                        </div>
                        <div class="alert alert-danger" role="alert" v-show="!passwordMatch">{{ changePassErrorText }}</div>

                        <div>
                            <span class="text-danger error-message" v-text="error_message"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click.prevent="processDeactivate" data-progress-text="Deactivating ... <i class='fa fa-circle-o-notch fa-spin fa-fw'></i>" autocomplete="off">Deactivate</button>
                </div>
            </div>
        </div>
    </div>

</span>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false,
            password: "",
            confirmPassword: "",
            passwordMatch: true,
            changePassErrorText: "Password & Confirm Password does not match.",
            changePasswordUrl: "/user/change-password",
            deactivateAccountUrl: "/user/deactivate-account",
            logInUrl: "/login",
            loading: false,
            reason: 'Select reason for deactivating',
            reason_text: "",
            deactivate: "",
            error_message: ""
        };
    },
    methods: {
        openEditProfileWindow: function () {
            this.isOpen = !this.isOpen;
        },

        editPassword: function () {
            $("#modal-resetpassword").modal();
        },

        changePassword: function () {
            this.passwordMatch = true;
            console.log("change pass....");
            if (!!!this.password) {
                this.changePassErrorText =
                    "Password or Confirm Password can't be empty.";
                this.passwordMatch = false;
                return;
            } else if (!!!this.confirmPassword) {
                this.changePassErrorText =
                    "Password or Confirm Password can't be empty.";
                this.passwordMatch = false;
                return;
            } else if (this.password != this.confirmPassword) {
                this.changePassErrorText =
                    "Password & Confirm Password does not match.";
                this.passwordMatch = false;
                console.log(
                    "does not match",
                    this.changePassErrorText,
                    this.passwordMatch
                );
                return;
            }

            this.loading = true;
            const that = this;
            $.ajax({
                type: "PUT",
                url: this.changePasswordUrl,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                dataType: "json",
                data: {
                    password: this.password,
                },
                success: function (response) {
                    that.loading = false;
                    that.giftCode = "";
                    const {
                        success,
                        message
                    } = response;

                    if (success) {
                        swal({
                            title: `Password updated.`,
                            text: message,
                            type: "success",
                            html: true,
                        });

                        $("#modal-resetpassword").modal("hide");
                    } else {
                        swal({
                            title: message,
                            type: "error",
                            html: true,
                        });
                    }
                    // swal.close();
                },
            });
        },
        deactivateAccount: function () {
            $("#modal-deactivate-account").modal();
        },
        selectReason: function () {
            if (this.reason != 'Others') {
                this.reason_text = this.reason;
            } else {
                this.reason_text = "";
            }
        },
        processDeactivate: function () {
            this.error_message = "";
            let self = this;

            if ((this.reason_text).length == 0) {
                this.error_message = "Please select reason for deactivation";
                return false;
            }

            if (this.deactivate != 'DEACTIVATE') {
                this.error_message = "Please enter the word 'DEACTIVATE' on the provided field.";
                return false;
            }

            $.ajax({
                type: "PUT",
                url: this.deactivateAccountUrl,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                dataType: "json",
                data: {
                    password: this.password,
                },
                success: function (response) {
                    if (response.success) {
                        swal({
                                title: `User Account Deactivated.`,
                                text: response.message,
                                type: "success",
                                html: true,
                            },
                            function () {
                                window.location.replace(self.logInUrl);
                            }
                        )
                    }
                },
            });

        }
    },
};
</script>

<style scoped>
.edit-profile-cog {
    cursor: pointer;
    position: absolute;
    right: 0;
    margin-right: 10px;
}

.edit-profile-dropdown {
    color: #d7d7d7 !important;
}

.edit-profile-dropdown:hover,
.edit-profile-dropdown:focus {
    background: none !important;
}

#modal-resetpassword .modal-title,
#modal-resetpassword label {
    color: #414141 !important;
}

#modal-resetpassword .alert-danger {
    font-size: 14px !important;
    font-weight: 500 !important;
}

#modal-deactivate-account .modal-title,
#modal-deactivate-account label {
    color: #414141 !important;
}

#modal-deactivate-account .alert-danger {
    font-size: 14px !important;
    font-weight: 500 !important;
}

.margin-top-20 {
    margin-top: 20px;
}

.text-yellow {
    color: #d4af37;
}

.error-message {
    color: #d9534f !important;
    font-size: 13px !important;
    text-transform: none !important;
}
</style>
