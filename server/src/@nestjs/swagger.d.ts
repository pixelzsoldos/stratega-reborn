declare module "@nestjs/swagger" {
  export class DocumentBuilder {
    setTitle(title: string): this;
    setDescription(description: string): this;
    setVersion(version: string): this;
    addBearerAuth(): this;
    build(): any;
  }

  export class SwaggerModule {
    static createDocument(app: any, config: any): any;
    static setup(path: string, app: any, document: any): void;
  }
}
