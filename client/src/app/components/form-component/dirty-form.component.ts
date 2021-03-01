import { Component, HostListener } from '@angular/core';
import { FormGroup } from '@angular/forms';

@Component({
    selector: 'app-form-component',
    template: ''
})
export abstract class DirtyFormWarnableComponent {
    public forms: FormGroup[];

    @HostListener('window:beforeunload', ['$event'])
    onBeforeUnload($event: Event): void {
        $event.preventDefault();

        if (!this.canLeave()) {
            $event.returnValue = false;
        }
    }

    abstract canLeave(): boolean;
}

export interface DirtyFormWarnableInterface {
    form: FormGroup;
}
