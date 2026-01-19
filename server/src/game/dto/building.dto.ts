export class BuildingDto {
  /** Name of the building being created. */
  name: string;

  /** The type of building (e.g., "house", "barracks"). */
  type: string;

  /** Optional coordinates within the game world. */
  x?: number;
  y?: number;
}
