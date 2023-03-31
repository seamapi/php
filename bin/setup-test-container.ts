import { getTestDatabase } from "./get-test-database";

import getPort from "get-port";
import { GenericContainer, Wait } from "testcontainers";
import getTestSvix from "./get-test-svix";
import knex from "knex";
import defaultAxios from "axios";

const SEAM_ADMIN_PASSWORD = "1234";

const startAndSeedServer = async () => {
  const database = await getTestDatabase();

  const svix = await getTestSvix({
    env: {
      SVIX_DB_DSN: database.internalDatabaseUrl,
      SVIX_JWT_SECRET: "somejwtsecret",
      SVIX_QUEUE_TYPE: "memory",
    },
    network: database.network,
  });

  const hostPort = await getPort();
  const serverUrl = `http://localhost:${hostPort}`;
  const seamConnectImage =
    process.env.SEAM_CONNECT_IMAGE ?? "ghcr.io/seamapi/seam-connect:latest";

  const server = await new GenericContainer(seamConnectImage)
    .withExposedPorts({
      container: hostPort,
      host: hostPort,
    })
    .withEnvironment({
      DATABASE_URL: database.internalDatabaseUrl,
      POSTGRES_DATABASE: database.databaseName,
      NODE_ENV: "test",
      SERVER_BASE_URL: serverUrl,
      PORT: hostPort.toString(),
      SEAMTEAM_ADMIN_PASSWORD: SEAM_ADMIN_PASSWORD,
      SVIX_ENDPOINT: svix.endpoint,
      SVIX_API_KEY: svix.apiKey,
      ENABLE_UNMANAGED_DEVICES: "true",
    })
    .withCommand(["start:for-integration-testing"])
    .withNetwork(database.network)
    .withNetworkAliases("api")
    .withWaitStrategy(Wait.forLogMessage("ready - started server"))
    .start();

  const db = knex(database.externalDatabaseUrl);

  const workspace = await db("seam.workspace")
    .where({ is_sandbox: true })
    .innerJoin(
      "seam.user_workspace",
      "seam.workspace.workspace_id",
      "seam.user_workspace.workspace_id"
    )
    .innerJoin("seam.user", "seam.user_workspace.user_id", "seam.user.user_id")
    .first();

  const axios = defaultAxios.create({
    baseURL: serverUrl,
  });

  const api_key = "seam_sandykey_0000000000000000000sand";

  (axios.defaults.headers as any).Authorization = `Bearer ${api_key}`;
};

startAndSeedServer();
