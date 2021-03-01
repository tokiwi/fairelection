import { Injectable } from '@angular/core';
import { CanDeactivate } from '@angular/router';
import { DirtyFormWarnableComponent } from '../../components/form-component/dirty-form.component';
import { Observable } from 'rxjs';
import { MatDialog } from '@angular/material/dialog';
import { ConfirmDialogFactory } from '../factory/confirm-dialog.factory';

@Injectable({
    providedIn: 'root'
})
export class DirtyFormGuard implements CanDeactivate<DirtyFormWarnableComponent> {
    constructor(
        private dialog: MatDialog,
        private confirmDialogFactory: ConfirmDialogFactory
    ) {
    }

    /**
     * This prevents user from leaving a form if the form is dirty
     */
    canDeactivate(component: DirtyFormWarnableComponent): Observable<boolean> | boolean {
        if (!component.canLeave()) {
            const dialog = this.confirmDialogFactory.create(
                'dialog.leave_dirty_form.title',
                'dialog.leave_dirty_form.content',
                'dialog.leave_dirty_form.confirm',
                'dialog.leave_dirty_form.cancel',
                'accent',
                'warn',
            );

            return dialog.afterClosed();
        }

        return true;
    }
}

