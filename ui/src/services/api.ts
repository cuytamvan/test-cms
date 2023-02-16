import axios, { AxiosInstance, AxiosRequestConfig } from 'axios';

import { DataInstance, DefResult } from '@/instances/ui.instance';
import config from './config';

type Method = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

interface CallbackError {
  (status: number): void;
}

export const Api = (callbackError?: CallbackError) => {
  const http: AxiosInstance = axios.create({
    baseURL: config.apiUrl,
    // headers: {
    //   'Access-Control-Allow-Origin': '*',
    //   'Content-Type': 'Application/json',
    // },
  });

  const request = async (method: Method, url: string, data?: DataInstance): Promise<DefResult | null> => {
    const token = localStorage.getItem('token');
    const config: AxiosRequestConfig = { method, url };

    if (token) Object.assign(config, { headers: { Authorization: `Bearer ${token}` } });
    if (data) Object.assign(config, { data });

    try {
      const request = await http.request(config);
      const result: DefResult = {
        status: request.status,
        ...request.data,
      };

      return result;
    } catch (error: any) {
      if (axios.isAxiosError(error)) {
        if (callbackError) callbackError(error.response?.status as number);

        const res = error.response?.data as { message: string; data?: any };

        return {
          status: error.response?.status,
          ...res,
        } as DefResult;
      } else {
        console.log(error);
        return null;
      }
    }
  };

  const get = async (endpoint: string): Promise<DefResult | null> => {
    return await request('GET', endpoint);
  };

  const post = async (endpoint: string, data?: DataInstance): Promise<DefResult | null> => {
    return await request('POST', endpoint, data);
  };

  const put = async (endpoint: string, data?: DataInstance): Promise<DefResult | null> => {
    return await request('PUT', endpoint, data);
  };

  const patch = async (endpoint: string, data?: DataInstance): Promise<DefResult | null> => {
    return await request('PUT', endpoint, data);
  };

  const del = async (endpoint: string): Promise<DefResult | null> => {
    return await request('DELETE', endpoint);
  };

  return {
    http,
    request,
    get,
    post,
    put,
    patch,
    del,
  };
};
