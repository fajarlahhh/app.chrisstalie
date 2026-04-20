<div>
    <!-- BEGIN login -->
    <div class="login login-v1">
        <!-- BEGIN login-container -->
        <div class="login-container">
            <!-- BEGIN login-header -->
            <div class="login-header">
                <div class="brand">
                    <div class="d-flex align-items-center">
                        <img src="/assets/img/login.png" class="h-70px" alt="" />
                    </div>
                </div>
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>
            </div>
            <!-- END login-header -->

            <!-- BEGIN login-body -->
            <div class="login-body">
                <!-- BEGIN login-content -->
                <div class="login-content fs-13px">
                    <form wire:submit="login">
                        <div class="form-floating mb-20px">
                            <input type="text" class="form-control fs-13px h-45px" id="uid" autocomplete="off"
                                wire:model="uid" placeholder="UID" required />
                            <label for="uid" class="d-flex align-items-center">UID</label>
                            @error('uid')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mb-20px">
                            <input type="password" class="form-control fs-13px h-45px" id="password" autocomplete="off"
                                wire:model="password" placeholder="Password" required />
                            <label for="password" class="d-flex align-items-center">Password</label>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-check mb-20px">
                            <input class="form-check-input" type="checkbox" id="remember" wire:model="remember" />
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-theme h-45px d-block w-100 btn-lg">Sign me in</button>
                        </div>
                    </form>
                    <x-alert />
                </div>
                <!-- END login-content -->
            </div>
            <!-- END login-body -->
        </div>
        <!-- END login-container -->
    </div>
    <!-- END login -->
</div>
