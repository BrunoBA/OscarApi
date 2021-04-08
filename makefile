IMAGE_NAME=oscar-api
USERNAME=brunoba93
HASH=$(shell git log -1 --format="%h")

login:
	docker login
build:
	docker build -t oscar-api .
tag: login
	docker tag $(IMAGE_NAME) $(USERNAME)/$(IMAGE_NAME):$(HASH)
push: build tag
	@echo "Pushing image to DOCKER HUB"
	docker push $(USERNAME)/$(IMAGE_NAME):$(HASH)
