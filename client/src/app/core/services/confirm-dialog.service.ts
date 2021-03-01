import { EventEmitter, Injectable } from '@angular/core';


@Injectable()
export class ConfirmDialogService {
    private confirmClickEvent: EventEmitter<boolean> = new EventEmitter();

    public emitCancelClick(): void {
        this.confirmClickEvent.emit(false);
    }

    public emitConfirmClick(): void {
        this.confirmClickEvent.emit(true);
    }

    public getConfirmClickEmitter(): EventEmitter<boolean> {
        return this.confirmClickEvent;
    }
}
