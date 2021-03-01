import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { UserService } from '../../core/services/user.service';
import { FormUtil } from '../../core/utils/form.util';
import { NotificationService } from '../../core/services/notification.service';
import { Router } from '@angular/router';
import { finalize, mergeMap } from 'rxjs/operators';
import { AuthService } from '../../core/services/auth.service';

@Component({
    selector: 'app-register',
    templateUrl: './register.component.html',
    styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

    public loading = false;
    public hidePassword = true;

    public form = this.fb.group({
        fullName: [null, [Validators.required, Validators.maxLength(255)]],
        email: [null, [Validators.required, Validators.email, Validators.maxLength(255)]],
        password: [null, [Validators.required, Validators.minLength(8), Validators.maxLength(4096)]],
    });

    constructor(
        private fb: FormBuilder,
        private userService: UserService,
        private notificationService: NotificationService,
        private router: Router,
        private authService: AuthService
    ) { }

    ngOnInit(): void {
    }

    public save(): void {
        if (this.form.valid) {
            this.loading = true;

            this.userService.register(this.form.value).pipe(
                finalize(() => this.loading = false),
                mergeMap(() => this.authService.login(this.form.value))
            ).subscribe(() => {
                this.notificationService.success('toast.user_account_created');

                void this.router.navigateByUrl('/elections/create');
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
