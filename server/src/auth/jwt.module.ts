export class JwtModule {
  static register(_options: any) {
    // Return a minimal DynamicModule stub for NestJS.
    return {
      module: JwtModule,
      providers: [],
      exports: [],
    };
  }
}
