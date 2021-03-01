import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { AuthService } from '../../core/services/auth.service';
import { NotificationService } from '../../core/services/notification.service';
import { FormUtil } from '../../core/utils/form.util';
import { Router } from '@angular/router';
import { finalize } from 'rxjs/operators';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
    public loading = false;
    public hidePassword = true;

    public form = this.fb.group({
        email: [null, [Validators.required, Validators.email, Validators.maxLength(255)]],
        password: [null, [Validators.required, Validators.maxLength(4096)]],
    });

    constructor(
        private fb: FormBuilder,
        private authService: AuthService,
        private notificationService: NotificationService,
        private router: Router
    ) { }

    ngOnInit(): void {
    }

    public login(): void {
        if (this.form.valid) {
            this.loading = true;

            this.authService.login(this.form.value).pipe(
                finalize(() => this.loading = false)
            ).subscribe(() => {
                this.notificationService.success('toast.welcome_home');

                void this.router.navigateByUrl('/');
            }, () => {
                this.notificationService.error('toast.wrong_username_or_password');
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
