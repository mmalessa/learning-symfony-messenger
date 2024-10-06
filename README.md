# Learning Symfony Messenger


## Kubernetes (K3D)
Use [github.com/mmalessa/local-kubernetes](https://github.com/mmalessa/local-kubernetes) -> (read README) && `make up` first


```shell
make k9s-init
make k9s-app-up
[...]
make k9s-app-down
make k9s-purge
```

```text
http://kafka-ui.localhost
```

```shell
kubectl logs -f <pod_name>
kubectl logs -f app=external-api
kubectl logs -f app=app-inbox-consumer
kubectl logs -f app=app-outbox-consumer
kubectl exec --stdin --tty <pod_name> -- sh
```
