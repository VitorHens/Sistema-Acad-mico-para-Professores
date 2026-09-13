/**
 * Classe ApiService para facilitar chamadas HTTP (GET, POST, PUT, DELETE) a APIs RESTful.
 * Suporta autenticação via token Bearer e fornece métodos reutilizáveis para diferentes tipos de requisições.
 */
export default class ApiService {
    #token;  // Atributo privado para armazenar o token de autenticação

    /**
     * Construtor da classe ApiService.
     * @param {string|null} token - Token de autenticação opcional para incluir no header Authorization.
     */
    constructor(token = null) {
        this.#token = token;  // Inicializa o token privado com o valor passado ou null
    }

    /**
     * Método para fazer uma requisição GET simples sem headers adicionais.
     * Útil para APIs públicas que não requerem autenticação.
     * @param {string} uri - URL do recurso para a requisição GET.
     * @returns {Promise<Object|Array>} Retorna o JSON obtido da resposta ou array vazio em caso de erro.
     */
    async simpleGet(uri) {
        try {
            const response = await fetch(uri);            // Faz a requisição HTTP GET
            const jsonObj = await response.json();        // Converte a resposta para JSON
            console.log("GET:", uri, jsonObj);             // Log para depuração
            return jsonObj;                                // Retorna o JSON obtido

        } catch (error) {
            console.error("Erro ao buscar dados:", error.message);  // Exibe erro no console
            return [];                                     // Retorna array vazio em caso de erro
        }
    }

    /**
     * Método para requisição GET com headers, incluindo token se presente.
     * Usado para APIs que exigem autenticação ou headers customizados.
     * @param {string} uri - URL do recurso para a requisição GET.
     * @returns {Promise<Object|Array>} Retorna JSON da resposta ou array vazio em caso de erro.
     */
    async get(uri) {
        try {
            // Configura headers padrão para JSON
            const headers = {
                "Content-Type": "application/json"
            };

            // Adiciona header Authorization se token estiver configurado
            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Faz requisição GET com headers configurados
            const response = await fetch(uri, {
                method: "GET",
                headers: headers
            });

            const jsonObj = await response.json();   // Converte resposta para JSON
            console.log("GET:", uri, jsonObj);       // Log para depuração
            return jsonObj;                           // Retorna JSON da resposta

        } catch (error) {
            console.error("Erro ao buscar dados:", error.message);
            return [];                               // Retorna array vazio em caso de erro
        }
    }

    /**
     * Método para buscar um recurso específico pelo ID via GET.
     * Monta a URL com o ID no final e faz a requisição.
     * @param {string} uri - URL base do recurso.
     * @param {string|number} id - Identificador do recurso a ser buscado.
     * @returns {Promise<Object|null>} Retorna JSON do recurso ou null em caso de erro.
     */
    async getById(uri, id) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Concatena URI com ID para buscar recurso específico
            const fullUri = `${uri}/${id}`;

            const response = await fetch(fullUri, {
                method: "GET",
                headers: headers
            });

            // Verifica se a resposta HTTP foi bem sucedida (status 2xx)
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const jsonObj = await response.json();
            console.log("GET BY ID:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao buscar por ID:", error.message);
            return null;  // Retorna null se houve erro na requisição
        }
    }

    /**
     * Método para enviar dados via POST para criar um novo recurso.
     * Envia o objeto JSON serializado no corpo da requisição.
     * @param {string} uri - URL do endpoint para POST.
     * @param {Object} jsonObject - Objeto a ser enviado como corpo JSON.
     * @returns {Promise<Object|Array>} Retorna JSON da resposta ou array vazio em caso de erro.
     */
    async post(uri, jsonObject) {
    try {
        const headers = {
            "Content-Type": "application/json"
        };

        if (this.#token) {
            headers["Authorization"] = `Bearer ${this.#token}`;
        }

        const response = await fetch(uri, {
            method: "POST",
            headers: headers,
            body: JSON.stringify(jsonObject)
        });

        // Primeiro pegamos como texto
        const texto = await response.text();

        console.log("POST:", uri);
        console.log("Status:", response.status);
        console.log("Resposta bruta:", texto);

        // Tenta transformar o texto em JSON
        try {
            const jsonObj = JSON.parse(texto);

            console.log("JSON recebido:", jsonObj);

            return jsonObj;

        } catch (error) {
            console.error("A API não retornou JSON.");
            console.error("Resposta recebida:", texto);

            return {
                success: false,
                error: "A API não retornou JSON",
                response: texto,
                status: response.status
            };
        }

    } catch (error) {
        console.error("Erro ao fazer POST:", error.message);

        return {
            success: false,
            error: error.message
        };
    }
}


    /**
     * Método para atualizar um recurso via PUT usando ID e objeto JSON.
     * @param {string} uri - URL base do recurso.
     * @param {string|number} id - ID do recurso a ser atualizado.
     * @param {Object} jsonObject - Dados atualizados a serem enviados no corpo da requisição.
     * @returns {Promise<Object|null>} Retorna JSON da resposta ou null em caso de erro.
     */
    async put(uri, id, jsonObject) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Monta URL final com ID
            const fullUri = `${uri}/${id}`;

            // Faz requisição PUT com corpo JSON
            const response = await fetch(fullUri, {
                method: "PUT",
                headers: headers,
                body: JSON.stringify(jsonObject)
            });

            const jsonObj = await response.json();
            console.log("PUT:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao enviar dados:", error.message);
            return null;  // Retorna null em caso de erro
        }
    }

    /**
     * Método para deletar um recurso via DELETE usando ID.
     * @param {string} uri - URL base do recurso.
     * @param {string|number} id - ID do recurso a ser deletado.
     * @returns {Promise<Object|null>} Retorna JSON da resposta ou null se não houver corpo ou erro.
     */
    async delete(uri, id) {
        try {
            const headers = {
                "Content-Type": "application/json"
            };

            if (this.#token) {
                headers["Authorization"] = `Bearer ${this.#token}`;
            }

            // Monta URL final com ID
            const fullUri = `${uri}/${id}`;

            // Executa requisição DELETE
            console.log("DELETE: " + fullUri);
            const response = await fetch(fullUri, {
                method: "DELETE",
                headers: headers
            });

            // Verifica sucesso da resposta
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            // Tenta converter resposta para JSON, mas se falhar retorna null
            const jsonObj = await response.json().catch(() => null);
            console.log("DELETE:", fullUri, jsonObj);
            return jsonObj;

        } catch (error) {
            console.error("Erro ao deletar dados:", error.message);
            return null;  // Retorna null em caso de erro
        }
    }

    /**
     * Getter para o token privado.
     * @returns {string|null} Retorna o token atual.
     */
    get token() {
        return this.#token;
    }

    /**
     * Setter para atualizar o token privado.
     * @param {string} value - Novo token a ser setado.
     */
    set token(value) {
        this.#token = value;
    }
}