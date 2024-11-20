import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IndexPrincipalComponent } from './index-principal.component';

describe('IndexPrincipalComponent', () => {
  let component: IndexPrincipalComponent;
  let fixture: ComponentFixture<IndexPrincipalComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IndexPrincipalComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(IndexPrincipalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
