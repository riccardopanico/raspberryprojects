DECLARE
   v_workspace VARCHAR2(50) := 'NIVA_RESTART'; -- Sostituisci con il nome del tuo workspace
   v_client_name VARCHAR2(50) := 'NIVA_RESTART_CLIENT'; -- Sostituisci con il nome desiderato per il client
   v_privilege_name VARCHAR2(50) := 'NIVA_RESTART_PRIVILEGE'; -- Privilegio per i dati degli impiegati
   v_role_name VARCHAR2(50) := 'NIVA_RESTART_ROLE'; -- Nome del ruolo
   v_oauth_client_description VARCHAR2(100) := 'Client OAuth per NIVA_RESTART_DESCRIPTION'; -- Descrizione per il client OAuth
   v_email VARCHAR2(100) := 'riccardo.panico@niva.it'; -- Sostituisci con la tua email
   v_pattern VARCHAR2(100) := '/*'; -- Sostituisci con il tuo pattern
BEGIN

    -- Creazione del ruolo
    ords.create_role(v_role_name);

    /*

        Per vedere se il ruolo è stato creato correttamente:
        select * from user_ords_roles;

    */

    -- Creazione del privilegio
    ords.create_privilege(
        p_name => v_privilege_name,
        p_role_name => v_role_name,
        p_label => v_privilege_name,
        p_description => 'Provide access to role data'
    );

    /*

        Per vedere se il privilegio e il ruolo sono stati creati correttamente:
        select * from user_ords_privileges ;

    */

    -- Creazione del client OAuth
    oauth.create_client(
        p_name => v_client_name,
        p_grant_type => 'client_credentials',
        p_description => v_oauth_client_description,
        p_support_email => v_email, -- Email del supporto
        p_privilege_names => v_privilege_name
    );

    /*

        Per vedere se il client è stato creato correttamente:
        select * from user_ords_clients;

    */

    -- Assegnazione del ruolo al client
    oauth.grant_client_role(
        p_client_name => v_client_name,
        p_role_name => v_role_name
    );

    -- Associazione privilegi a modulo

    ords.create_privilege_mapping(
        p_privilege_name => v_privilege_name,
        p_pattern => v_pattern
    );

    -- Disassociazione mapping

    -- ords.delete_privilege_mapping(
    --     p_privilege_name => v_privilege_name,
    --     p_pattern => v_pattern
    -- );

    COMMIT;
END;
