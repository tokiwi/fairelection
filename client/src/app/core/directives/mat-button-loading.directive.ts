import {
    ComponentFactory,
    ComponentFactoryResolver, ComponentRef,
    Directive,
    Input,
    OnChanges,
    Renderer2,
    SimpleChanges,
    ViewContainerRef
} from '@angular/core';
import { MatButton } from '@angular/material/button';
import { MatProgressSpinner } from '@angular/material/progress-spinner';
import { ThemePalette } from '@angular/material/core';
import { LoadingService } from '../services/loading.service';

@Directive({
    selector: '[appMatButtonLoading]'
})
export class MatButtonLoadingDirective implements OnChanges {
    @Input()
    loading = false;

    @Input()
    disabled = false;

    @Input()
    color: ThemePalette;

    private spinnerFactory: ComponentFactory<MatProgressSpinner>;
    private spinner: ComponentRef<MatProgressSpinner>;

    constructor(
        private matButton: MatButton,
        private componentFactoryResolver: ComponentFactoryResolver,
        private viewContainerRef: ViewContainerRef,
        private renderer: Renderer2,
        private loadingService: LoadingService
    ) {
        this.spinnerFactory = this.componentFactoryResolver.resolveComponentFactory(MatProgressSpinner);
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (!changes.loading) {
            return;
        }

        if (changes.loading.currentValue) {
            (this.matButton._elementRef.nativeElement as HTMLElement).classList.add('mat-loading');
            this.matButton.disabled = true;
            this.createSpinner();
        } else if (!changes.loading.firstChange) {
            (this.matButton._elementRef.nativeElement as HTMLElement).classList.remove('mat-loading');
            this.matButton.disabled = this.disabled;
            this.destroySpinner();

            this.loadingService.stopLoading();
        }
    }

    private createSpinner(): void {
        if (!this.spinner) {
            this.spinner = this.viewContainerRef.createComponent(this.spinnerFactory);
            this.spinner.instance.color = this.color;
            this.spinner.instance.diameter = 20;
            this.spinner.instance.mode = 'indeterminate';
            this.renderer.appendChild(this.matButton._elementRef.nativeElement, this.spinner.instance._elementRef.nativeElement);
        }
    }

    private destroySpinner(): void {
        if (this.spinner) {
            this.spinner.destroy();
            this.spinner = null;
        }
    }
}
