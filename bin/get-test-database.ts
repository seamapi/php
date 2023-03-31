import {
  Network,
  GenericContainer,
  StartedTestContainer,
  StartedNetwork,
} from "testcontainers";
import knex from "knex";
import { customAlphabet } from "nanoid";

let container: StartedTestContainer;
let network: StartedNetwork;
const nanoid = customAlphabet("1234567890abcdef", 10);
export const getTestDatabase = async () => {
  if (!container) {
    network = await new Network().start();

    container = await new GenericContainer("postgres:13")
      .withExposedPorts(5432)
      .withEnvironment({ POSTGRES_HOST_AUTH_METHOD: "trust" })
      .withNetwork(network)
      .withNetworkAliases("database")
      .withCommand(["-c", "max_connections=1000"])
      .start();
  }

  const db = knex(
    `postgresql://postgres:@${container.getHost()}:${container.getMappedPort(
      5432
    )}/postgres`
  );

  const databaseName = `seam_test_${nanoid()}`;

  await db.raw(`CREATE DATABASE ${databaseName};`);
  await db.raw("ALTER ROLE postgres SET search_path TO public;");

  return {
    internalDatabaseUrl: `postgresql://postgres:@database:5432/${databaseName}`,
    externalDatabaseUrl: `postgresql://postgres:@${container.getHost()}:${container.getMappedPort(
      5432
    )}/${databaseName}`,
    databaseName,
    network: network,
  };
};
