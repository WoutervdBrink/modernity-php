export type Lookup = (nodeIdx: number) =>
    | {
          name: string;
          backgroundColor: CSSStyleDeclaration['backgroundColor'];
      }
    | undefined;
export function makeLookup(source: string[]): Lookup {
    return (idx: number) => {
        if (idx < 0 || idx > source.length) return undefined;
        return {
            name: source[idx],
            backgroundColor: `hsl(${(idx * 330) / source.length}, 100%, 80%) !important`,
        };
    };
}
